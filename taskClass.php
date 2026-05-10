<?php


class Task{

    public $jsonFile;

     function __construct($jsonFile){
        $this->jsonFile = $jsonFile;

    }

    public function add($task, $status = "todo"){
    
        $lastId = 0;
        $current = json_decode($this->jsonFile, true);

        // Checks if file is empty and gets the id of the last item to aadd to the next
        if (!empty($current)){
            $ids = array_keys($current);
            $lastId = end($ids) + 1;
            
        }
        else{
            
            $current = [];
        };


        $taskToAdd = [
                "id" => $lastId,
                "description" => $task,
                "status" => $status,
                "createdAt" => date("Y/m/d"),
                "updatedAt" => date("Y/m/d")
            ];


        
        $current[] = $taskToAdd;
        $json[] = json_encode($current, JSON_PRETTY_PRINT);


        file_put_contents('tasks.json', $json);

        return "Task added successfully (ID: {$taskToAdd["id"]})";
    }



    function  delete($id){

        $taskToDelete = json_decode($this->jsonFile, true);

        if (!isset($taskToDelete[$id])){
            return "That ID does not exist";
        }

        unset($taskToDelete[$id]);

        file_put_contents('tasks.json', json_encode($taskToDelete, JSON_PRETTY_PRINT));

        return "Task deleted successfully (ID: $id)";

    }



    function update($id, $newTask){

        $updatedTask = json_decode($this->jsonFile, true);

        if (!isset($updatedTask[$id])){
            return "That ID does not exist";
        }

        $updatedTask[$id]["description"] = $newTask;
        $updatedTask[$id]["updatedAt"] = date("Y/m/d");
        file_put_contents('tasks.json', json_encode($updatedTask, JSON_PRETTY_PRINT));
        return "Task updated successfully (ID: $id)";
    }


    function mark($mark, $id){

        $taskToMark = json_decode($this->jsonFile, true);

        if (!isset($taskToMark[$id])){
            return "That ID does not exist";
        }

        switch ($mark){
            case "mark-in-progress":
                $taskToMark[$id]["status"] = "in_progress";
                $taskToMark[$id]["updatedAt"] = date("Y/m/d");
                break;
            case "mark-done":
            $taskToMark[$id]["status"] = "done";
                $taskToMark[$id]["updatedAt"] = date("Y/m/d");
                break; 
        }
        

        file_put_contents('tasks.json', json_encode($taskToMark, JSON_PRETTY_PRINT));

        return "Task marked successfully (ID: $id)";
    }


    function listTasks($action = null){

        $tasks = json_decode($this->jsonFile, true);
        $taskList = "";


        if (empty($tasks)){
            return "File is empty";
        }

        foreach ($tasks as $task){

        switch ($action){
            case "done":
                if ($task["status"] == "done"){
                    $taskList .= "\n {$task["id"]}: {$task["description"]} | {$task["status"]}\n";
                }
                break;
            case "todo":
                if ($task["status"] == "todo"){
                    $taskList .= "\n {$task["id"]}: {$task["description"]} | {$task["status"]} \n";
                } 
                break;
            case "in_progress":
                if ($task["status"] == "in_progress"){
                    $taskList .= "\n {$task["id"]}: {$task["description"]} | {$task["status"]} \n";
                }
                break;
            default:
                $taskList .= "\n {$task["id"]}: {$task["description"]}  | {$task["status"]} \n";

        }
            
        }

        return $taskList;
    }



}


?>