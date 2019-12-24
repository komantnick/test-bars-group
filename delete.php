<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Удалить подразделение</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>

</head>
<body>
<?php

$json_data = file_get_contents('lpu.json');
$json = json_decode($json_data,true);
$json = $json["LPU"];
//option'ы для списка
$html = "";
function get_element($id,$json){
    
    //$id=10903;
    foreach ($json as $value){
        if ($value["id"]==$id){
            return $value;
        }
    }
    //если не нашли
    return [];
}
function delete_cascade($id,$json){
//проходим по всем списку ключ-значение
foreach ($json as $key=>$value){
    if ($value["id"]==$id){
        //если находим нужную запись - обновляем значения
        unset($json[$key]);
    }
    if ($value["hid"]==$id){
        delete_cascade($value["id"],$json);
    }
}
return $json;
}
if ($_POST){
    
    $json = delete_cascade($_POST["id"],$json);
    //запись в файл
    $output=[];
    $output["LPU"]=$json;
    //если удалось запись в файл
    if (file_put_contents("lpu.json", json_encode($output,JSON_UNESCAPED_UNICODE))){
        echo "Запись удалена<br>
                <a href='index.php'>Вернуться к списку</a><br>";
    }
}


//находим элемент в зависимости от типа запроса
if (!empty($_GET["id"])){$value = get_element($_GET["id"],$json);}
else {$value = get_element($_POST["id"],$json);}
//Чистим POST
$_POST=[];
?>

<?php if (!empty($_GET)): ?>
    <h3>Вы хотите удалить данный элемент и все его подразделения?</h3>
<form action="delete.php" method="post">

<input type="text" required name="id" value="<?= $value["id"];?>" class="form-control hidden " >
  
  <button type="submit" class="btn btn-primary">Удалить</button>
</form>
<?php endif;?>
</body>

