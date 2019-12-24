<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
function get_random_id($json){
    $id=rand(10000, 9999999);
    //$id=10903;
    foreach ($json as $value){
        if ($value["id"]==$id){
            get_random_id($json);
        }
    }
    return $id;
}
$json_data = file_get_contents('lpu.json');
$json = json_decode($json_data,true);
$json = $json["LPU"];
//option'ы для списка
$html = "";

if ($_POST){
    $id=get_random_id($json);
    $json[]=array(
        "id" => $id,
        "hid" => $_POST["hid"],
        "full_name" => $_POST["full_name"],
        "address" => $_POST["address"],
        "phone" =>  $_POST["phone"],
    );
    //запись в файл
    $output=[];
    $output["LPU"]=$json;
    //если удалось запись в файл
    if (file_put_contents("lpu.json", json_encode($output,JSON_UNESCAPED_UNICODE))){
        echo "Запись добавлена<br>
                <a href='index.php'>Вернуться к списку</a><br>";
    }
}


//Чистим POST
$_POST=[];
foreach ($json as $value) {
    //чтобы options не прерывались при наличии HTML-тега делаем strip_tags
    $html.="<option value='".$value["id"]."'>". strip_tags ($value["full_name"])."</option>";

  }
//print_r($html);exit;
?>
<form action="create.php" method="post">
  <div class="form-group">


  <label for="hid">Родительское подразделение</label>
  <select class="form-control" name="hid"  placeholder="Введите подразделение">
  <option value="0" selected="selected">Не выбирать</option>
  <?= $html; ?>
  </select> 
    
    
  </div>
  <div class="form-group">
    <label for="full_name">Имя подразделения</label>
    <input type="text" required name="full_name" class="form-control" placeholder="Введите имя подразделения">
  </div>
  <div class="form-group">
    <label for="address">Адрес</label>
    <input type="text" required name="address" class="form-control" placeholder="Введите адрес подразделения">
  </div>
  <div class="form-group">
    <label for="phone">Телефон</label>
    <input type="text" required name="phone" class="form-control" placeholder="Введите телефон подразделения">
  </div>
  
  <button type="submit" class="btn btn-primary">Добавить</button>
</form>
</body>