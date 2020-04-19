

## Tasks [/tasks]

### List tasks [GET /tasks?user_id={user_id}&due_date={due_date}]

+ Parameters
    + user_id: `0b01f518-3092-434a-8521-8f7496edf0b0` (uuid, required) - Show tasks for specific user.
    + due_date: `2020-04-16` (string, optional) - Show tasks scheduled for specific date.

+ Response 200 (application/json)
    + Attributes (array[Task], fixed-type)

### Create a task [POST /tasks]

+ Request  No due day. (application/json)

    + Attributes (CreateTaskRequest)

+ Request Schedule for a due day. (application/json)

    + Attributes (CreateAndScheduleTaskRequest)


+ Response 201 (application/json)

    + Body

            {
                "created": true
            }

+ Response 400 (application/json)

    + Body

            {
                "errors": [
                    "userId: This is not a valid UUID."
                ]
            }

+ Response 409 (application/json)

    + Body

            {
                "errors": ["Unable to schedule task for the past."]
            }
    
### Update a task [PATCH /tasks]

+ Request Update. (application/json)

    + Attributes (UpdateTaskRequest)

+ Request Complete. (application/json)

    + Attributes(object)
        + completed: `true` (boolean, optional)

+ Request Reschedule. (application/json)

    + Attributes(object)
        + due_date: `2020-04-18` (string, nullable, optional)

+ Response 200 (application/json)

    + Body

            {}

+ Response 409 (application/json)

    + Body

            {
                "errors": ["Completed task can't be scheduled."]
            }

## Data Structures

### Task
+ id: `0b01f518-3092-434a-8521-8f7496edf0b0` (string, required)
+ title: `Task title` (string, required)
+ completed: `true` (boolean, required)
+ due_date: `2020-04-18` (string, required)
+ owner: `0b01f518-3092-434a-8521-8f7496edf0b0` (string, required)

### CreateTaskRequest
+ title: `Task title` (string, required)
+ user_id: `0b01f518-3092-434a-8521-8f7496edf0b0` (string, required)

### CreateAndScheduleTaskRequest
+ title: `Task title` (string, required)
+ user_id: `0b01f518-3092-434a-8521-8f7496edf0b0` (string, required)
+ due_date: `2020-04-18` (string, nullable, optional)

### UpdateTaskRequest
+ completed: `true` (boolean, optional)
+ due_date: `2020-04-18` (string, optional)