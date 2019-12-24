<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Обновить подразделение</title>
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

if ($_POST){
    //проходим по всем списку ключ-значение
    foreach ($json as $key=>$value){
        if ($value["id"]==$_POST["id"]){
            //если находим нужную запись - обновляем значения
            $json[$key]=array(
                "id" => $_POST["id"],
                "hid" => $value["hid"],
                "full_name" => $_POST["full_name"],
                "address" => $_POST["address"],
                "phone" =>  $_POST["phone"],
            );
        }
    }
    
    //запись в файл
    $output=[];
    $output["LPU"]=$json;
    //если удалось запись в файл
    if (file_put_contents("lpu.json", json_encode($output,JSON_UNESCAPED_UNICODE))){
        echo "Запись добавлена<br>
                <a href='index.php'>Вернуться к списку</a><br>";
    }
}


//находим элемент в зависимости от типа запроса
if (!empty($_GET["id"])){$value = get_element($_GET["id"],$json);}
else {$value = get_element($_POST["id"],$json);}
//Чистим POST
$_POST=[];
//Комменты к форме. strip tags используем для удаления html-тегов для корректной обработки формы. Заменяем " на &quot по той же причине
?>
<form action="update.php" method="post">
<input type="text" required name="id" value="<?= $value["id"];?>" class="form-control hidden " >


 
  <div class="form-group">
    <label for="full_name">Имя подразделения</label>
    <input type="text" required name="full_name" value="<?=  strip_tags(str_replace('"', '&quot;', $value["full_name"]));?>" 
    class="form-control" placeholder="Введите имя подразделения">
  </div>
  <div class="form-group">
    <label for="address">Адрес</label>
    <input type="text" required name="address" value="<?= $value["address"];?>"  class="form-control" placeholder="Введите адрес подразделения">
  </div>
  <div class="form-group">
    <label for="phone">Телефон</label>
    <input type="text" required name="phone"  value="<?= $value["phone"];?>" class="form-control" placeholder="Введите телефон подразделения">
  </div>
  
  <button type="submit" class="btn btn-primary">Добавить</button>
</form>
</body>