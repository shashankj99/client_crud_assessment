## client_crud_assessment_api
This repository contains the api details for CRUD part of client module of the application as mentioned in the assessment task. This repository follows a specific design principle that I've been practicing over the year.

When you open the **ClientController** you'll notice there aren't many functions defining CRUD operations. It is because all the operations needed to perform (create & read in this task) have been written in the **BaseController** class.

As you can see in the ClientController, no method has been defined. You can easily override the method as per your needs.

However you'll need to define your model and your controller rules in the repository of the related module.

This pattern works well for large application which have been broken down into several modules as well as with small applications as well.

- A functional test cases has been written.
- You can also find a Dockerfile attached to this repo.

##### PS: I tried to log using logentries but it asked for the work email and also didn't have much time to implement all the bonus points mentioned in the task.

## Deployment

This repo has been deployed to heroku. The link to the live site is [https://client-crud-assessment.herokuapp.com/api/clients](https://client-crud-assessment.herokuapp.com/api/clients).

- There are many ways to deply to the heroku. I chose the easiest way, i.e. connect my git repo to the server.
- I then created a Procfile that contains commands to start my app.
