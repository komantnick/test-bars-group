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
// Функция для представления массива в виде древовидной таблицы
function get_tree($tree, $pid,$level)
{
    $html = '';
 
    foreach ($tree as $row)
    {
        if ($row['hid'] == $pid)
        {

           // $html.='<tr class="node">';
            $html.= "<tr  class='node' data-hid='" . $row['hid'] . "' data-id='" . $row['id'] . "' data-level='" . $level . "'></td>";
            $html.="<td width='5%' class='marks'> <span class='glyphicon glyphicon-plus children-plus '></span> <span class='glyphicon glyphicon-minus children-minus hidden'></span></td>";
            $html.= "<td width='30%' data-indent='" . $level . "'>" . $row["full_name"] . "</td>";
            //data-indent - отступ уровня
            $html.= "<td width='30%' data-indent='" . $level . "'>" . $row["address"] . "</td>";
            $html.= "<td width='10%' data-indent='" . $level . "'>" . $row["phone"] . "</td>";
            $html.="<td width='5%'>
            <a href='update.php?id=".$row["id"]."'><span class='glyphicon glyphicon-edit'></span>Изменить<br>
            <a href='delete.php?id=".$row["id"]."'><span class='glyphicon glyphicon-trash'></span>Удалить<br>
            </td>";
            $level = $level + 1; //Увеличиваем уровень вложености
            $html .= '    ' . get_tree($tree, $row['id'],$level);
            $level = $level - 1; //Уменьшаем уровень вложености, ведь мы обошли все дерево
            $html.='</tr>';
        }
    }
 
    return $html ? $html . "\n" : '';
}

$json_data = file_get_contents('lpu.json');
$json = json_decode($json_data,true);
$json = $json["LPU"];
                 
?>
<a href='create.php'><span class='glyphicon glyphicon-asterisk'></span>Добавить новое подразделение</a><br>
<table class="table table-bordered thead-dark">
    <thead>
      <tr>
        <th width='5%'></th>
        <th width='30%' >Наименование</th>
        <th width='30%'>Адрес</th>
        <th width='25%'>Телефон</th>
        <th width='10%'>Действия</th>
      </tr>
      <tr>
      <td width='5%'><a><span class="f-open">Развернуть<br> все</span><span class="f-close hidden">Свернуть<br> все</span></a></td>
      <td width='30%'></td>
      <td width='30%'></td>
      <td width='25%'></td>
      <td width='10%'></td>
      </tr>
    </thead>
    <tbody class="tabtab hidden">
    <?= get_tree($json, 0,0); ?>
    </tbody>
    <table>


 
    <script>
    $(function() {
  //      при раскрытии списка скрываем надпись "Раскрыть все", раскрываем надпись "Скрыть все" и таблицу
  $(".f-open").click(function() {
  	/
      $(".tabtab").removeClass('hidden');
      $(".f-open").addClass('hidden');
      $(".f-close").removeClass('hidden');
      $('.tabtab tr').each(function(item,index){
    var hid = $(this).data("hid");
    if (hid) {$(this).addClass('hidden');}
    //Не скрываем плюсы только там, где есть дочерние элементы
    if ($(this).next().data('level')!== undefined && $(this).data('level')>=$(this).next().data('level')){
       
        $(this).find('td.marks .children-minus').addClass('hidden');
        $(this).find('td.marks .children-plus').addClass('hidden');
    }
    else if ($(this).next().data('level')=== undefined){
        $(this).find('td.marks .children-minus').addClass('hidden');
        $(this).find('td.marks .children-plus').addClass('hidden');
    }

});
  });
  //скрываем надпись "Скрыть все", скрываем таблицу и вложенные списки
  $(".f-close").click(function() {
    $(".tabtab").addClass('hidden');
    $(".f-close").addClass('hidden');
    $(".f-open").removeClass('hidden');
    $('.tabtab tr').each(function(item,index){
        $(this).find('td.marks .children-minus').addClass('hidden');
        $(this).find('td.marks .children-plus').removeClass('hidden');
});
    

  });
});
    //раскрываем список дочерних элементов
$(".children-plus").click(function() {
    var data_id = $(this).closest('tr').attr('data-id');  //ид узла
    console.log(data_id);
    $('.tabtab tr').each(function(){
    var hid = $(this).data("hid");
    if (hid==data_id) {$(this).removeClass('hidden');}    
    });
    $(this).addClass('hidden');
    console.log($(this).closest('td').find('.children-minus').removeClass('hidden'));

});
//закрываем список дочених элементов
$(".children-minus").click(function() {
    var data_id = $(this).closest('tr').attr('data-id'); //ид узла
    $('.tabtab tr').each(function(){
    var hid = $(this).data("hid"); //родительский ид
    if (hid==data_id) {$(this).addClass('hidden');}    
    });
    $(this).addClass('hidden');
    console.log($(this).closest('td').find('.children-plus').removeClass('hidden'));

});

//настраиваем отступ дочерних элементов
$("td")
    .css("padding-left", function (index) {
    return 30 * parseInt($(this).data("indent")) + 5+ "px";
});

    </script>
</body>
</html>