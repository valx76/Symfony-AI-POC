Small POC to check Google Gemini usage in Symfony 7.2.
<br/>
-> This project is an API used to generate an ER diagram of a database model, sent in JSON.


# Workflow

1. The user sends the database model in JSON.
2. The controller will save it into DB and send it to a RabbitMQ queue.
3. A consumer will transform the database model into a LLM prompt sent to Google Gemini.<br/>
   The LLM response is then published on a Mercure hub to inform the client when it's done.


# Usage

- `docker compose up` to start the different containers.
- `symfony server:start` to start the PHP dev server.
- `symfony console messenger:consume -vv async` to start the consumer.

Code quality:
- `make php-cs-fixer`
- `make phpstan` (level 10!)


# TODO

- Add the remaining tests
- Add a simple UI


# Request example (POST)

````json
{
  "name": "My db",
  "tables": [
    {
      "name": "Table1",
      "fields": [
        {"name": "Field1_1", "type": "table", "foreignKeyExtra": "Table2"},
        {"name": "Field1_2", "type": "string"},
        {"name": "Field1_3", "type": "float"}
      ]
    },
    {
      "name": "Table2",
      "fields": [
        {"name": "Field2_1", "type": "boolean"},
        {"name": "Field2_2", "type": "float"}
      ]
    },
    {
      "name": "Table3",
      "fields": [
        {"name": "Field3_1", "type": "integer"}
      ]
    }
  ]
}
````
