## Sample Application with Sencha ExtJS 7.7 and Symfony 6.2 Backend

This repository showcases a sample application that illustrates the integration of Sencha ExtJS for the frontend and Symfony for the backend. The primary objective of this project is to implement CRUD operations on a set of sample data. The data is initialized during migration, and it includes a history of events for each sample.

### Front-end Implementation

The front-end is built using Sencha ExtJS 7.7

https://docs.sencha.com/extjs/7.7.0/index.html


### Back-end Implementation

The Symfony backend is responsible for handling CRUD operations on the sample data. The structure of the SQL database is designed to store the main object's ID, creation date, current status, and the history of events in the form of status names and their creation dates.

#### API Endpoints:

- **Get All Samples:** `GET /sample`
- **Get All History:** `GET /history`
- **Get Sample by ID:** `GET /sample/{id}`
- **Get Sample by Status:** `GET /sample/status/{status}`
- **Get Sample by Historical Status:** `GET /sample/history/status/{status}`
- **Get Sample by Name:** `GET /sample/name/{name}`
- **Get Sample by Date:** `GET /sample/date/{type}/{date}`
- **Add Sample:** `POST /sample`
- **Edit Sample:** `PUT /sample/{id}`

For detailed information on each endpoint, refer to the Symfony controller `SampleControllerApi`.

### Database Structure:

The database contains two entities: `Sample` and `History`. The `Sample` entity stores the main data, while the `History` entity stores historical status information.

### How to Run:

To start the project, follow these steps:

1. Run the following command to build the Docker container and install dependencies:
```bash
make start
```

2. Start the Docker container:
```bash
make up
```

3. Run migration
```bash
make migration
```

4. Ext JS 7.7 start manual - from zip file:
https://docs.sencha.com/extjs/7.7.0/guides/getting_started/getting_started_with_zip.html

Download Sencha Cmd CLI
https://www.sencha.com/products/extjs/evaluate/

5. Install Sencha Cmd CLI
In linux:
```bash
bash ./SenchaCmd-7.7.0.36-linux-amd64.sh
```

6. Generate the ext sencha-sdks
```bash
sencha generate app --ext sencha-sdks vendor/sencha-sdks
```
7. Install ext (vendor) to extJs app
```bash
cd public
sencha app install --framework=../vendor/sencha-sdks/ext
```


### Run app

```plaintext
http://localhost:8000
```
# DB 
name: test
pass: test1234

# Container console:
```bash
make console
```

# Run test