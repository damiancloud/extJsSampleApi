<?php
namespace App\Controller;

use App\Entity\Sample;
use App\Entity\History;
use App\Repository\SampleRepository;
use App\Repository\HistoryRepository;
use App\Enum\SampleStatusEnum;
use App\Enum\SampleDateEnum;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;

class SampleControllerApi extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager
    ){
    }

    #[Route('/sample', name: 'get_all_sample', methods: 'GET')]
    public function getAllSample(SampleRepository $sampleRepository): Response
    {
        return $this->json(
            $sampleRepository->findAll(), 
            Response::HTTP_OK, 
            ['Content-Type' => 'application/json']
        );
    }

    #[Route('/history', name: 'get_all_history', methods: 'GET')]
    public function getAllHistory(HistoryRepository $historyRepository): Response
    {
        return $this->json(
            $historyRepository->findAll(), 
            Response::HTTP_OK, 
            ['Content-Type' => 'application/json']
        );
    }

    #[Route('/sample/{id<\d+>}', name: 'get_sample', methods: 'GET')]
    public function getSample(int $id, SampleRepository $sampleRepository): Response
    {
        try {
            $sample = $sampleRepository->find($id);
    
            if (!$sample) {
                throw new NotFoundHttpException('Sample not found');
            }
    
            return $this->json($sample, Response::HTTP_OK, []);
        } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/sample/status/{status}', name: 'get_sample_by_status', methods: 'GET')]
    public function getSampleByStatus(string $status = SampleStatusEnum::STATUS_ONE, SampleRepository $sampleRepository, Request $request): Response
    {
        return $this->searchSampleByStatus($request->getPathInfo(), $status, $sampleRepository);
    }

    #[Route('/sample/history/status/{status}', name: 'get_sample_by_history_status', methods: 'GET')]
    public function getSampleByHistoryStatus(string $status = SampleStatusEnum::STATUS_ONE, SampleRepository $sampleRepository, Request $request): Response
    {
        return $this->searchSampleByStatus($request->getPathInfo(), $status, $sampleRepository);
    }

    private function searchSampleByStatus(string $path, string $status, SampleRepository $sampleRepository): Response
    {
        try {
            if (!SampleStatusEnum::isValid($status)) {
                throw new \InvalidArgumentException('Invalid status', Response::HTTP_BAD_REQUEST);
            }

            $findMethod = str_starts_with($path, '/samples/history/status/') ? 'findByHistoryStatus': 'findByStatus';
            $sample = $sampleRepository->$findMethod($status);
    
            if (!$sample) {
                throw new HttpException(Response::HTTP_NOT_FOUND, 'Sample not found');
            }
    
            return $this->json($sample, Response::HTTP_OK, []);
        } catch (\Exception $e) {
                $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
                return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('/sample/name/{name}', name: 'get_sample_by_name', methods: 'GET')]
    public function getSampleByName(string $name, SampleRepository $sampleRepository): Response
    {
        try {
            $sample = $sampleRepository->findByName($name);
    
            if (!$sample) {
                throw new NotFoundHttpException('Sample not found');
            }
    
            return $this->json($sample, Response::HTTP_OK, []);
        } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/sample/date/{type}/{date<^\d{4}-\d{2}-\d{2}$>}', name: 'get_sample_by_date', methods: 'GET')]
    public function getSampleByDate(string $date, string $type, SampleRepository $sampleRepository): Response
    {
        try {
            if (!SampleDateEnum::isValid($type)) {
                throw new \InvalidArgumentException('Invalid date type', Response::HTTP_BAD_REQUEST);
            }

            $sample = $sampleRepository->findByDate($date, 'date' . ucfirst($type));
    
            if (!$sample) {
                throw new NotFoundHttpException('Sample not found');
            }
    
            return $this->json($sample, Response::HTTP_OK, []);
        } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/sample', name: 'add_sample', methods: 'POST')]
    public function addSample(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $requiredFields = ['name', 'status'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new \InvalidArgumentException("Field '$field' is required.", Response::HTTP_BAD_REQUEST);
                }
            }

            if (!SampleStatusEnum::isValid($data['status'])) {
                throw new \InvalidArgumentException('Invalid status', Response::HTTP_BAD_REQUEST);
            }

            $sample = new Sample();
            $sample->setName($data['name']);
            $sample->setDateCreated(new \DateTime('now'));
            $sample->setStatus($data['status']);
            if (isset($data['date'])) {
                $dateLast = \DateTime::createFromFormat('Y-m-d', $data['date']);
                if ($dateLast instanceof \DateTime) {
                    $sample->setDateLast(new \DateTime($data['date']));
                } else {
                    $dateLast = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']);
                    if ($dateLast instanceof \DateTime)
                        $sample->setDateLast(new \DateTime($data['date']));
                }
            }

            $this->entityManager->persist($sample);
            $this->entityManager->flush();

            return $this->json(['message' => 'Sample added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('/sample/{id<\d+>}', name: 'edit_sample', methods: ['PUT'])]
    public function editSample(int $id, Request $request, SampleRepository $sampleRepository): Response
    {
        try {
            $sample = $sampleRepository->find($id);

            if (!$sample) {
                throw new NotFoundHttpException('Sample not found');
            }

            $data = json_decode($request->getContent(), true);

            if (!SampleStatusEnum::isValid($data['status'])) {
                throw new \InvalidArgumentException('Invalid status', Response::HTTP_BAD_REQUEST);
            }

            if (isset($data['name'])) {
                $sample->setName($data['name']);
            }

            if (isset($data['date'])) {
                $dateLast = \DateTime::createFromFormat('Y-m-d', $data['date']);
                if ($dateLast instanceof \DateTime) {
                    $sample->setDateLast(new \DateTime($data['date']));
                } else {
                    $dateLast = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']);
                    if ($dateLast instanceof \DateTime)
                        $sample->setDateLast(new \DateTime($data['date']));
                }
            }

            if (isset($data['status'])) {
                $sample->setStatus($data['status']);
            }

            $history = new History();
            $history->setSample($sample);
            $history->setDate(new \DateTime('now'));
            $history->setStatus($sample->getStatus());

            $this->entityManager->persist($sample);
            $this->entityManager->persist($history);
            $this->entityManager->flush();

            return $this->json(['message' => 'Sample updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }
    }
}