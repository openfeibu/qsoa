<?php
/**
 * 生成mysqli数据字典
 */
header("Content-type:text/html;charset=utf-8");
// 配置数据库
$database = array();
$database['DB_HOST'] = '39.108.144.9:3306';
$database['DB_NAME'] = 'qsoa';
$database['DB_USER'] = 'feibukeji';
$database['DB_PWD'] = 'ladfjeuwf71741s';
$mysqli_conn = @mysqli_connect("{$database['DB_HOST']}", "{$database['DB_USER']}", "{$database['DB_PWD']}") or die("mysqli connect is error.");
mysqli_select_db( $mysqli_conn,$database['DB_NAME']);
$result = mysqli_query($mysqli_conn,'show tables');
mysqli_query($mysqli_conn,"set names utf8");
// 取得所有表名
while ($row = mysqli_fetch_array($result))
{
  $tables[]['TABLE_NAME'] = $row[0];
}
// 循环取得所有表的备注及表中列消息
foreach($tables as $k => $v)
{
  $sql = 'SELECT * FROM ';
  $sql .= 'information_schema.TABLES ';
  $sql .= 'WHERE ';
  $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database['DB_NAME']}'";
  $table_result = mysqli_query($mysqli_conn,$sql);
  while ($t = mysqli_fetch_array($table_result))
  {
    $tables[$k]['TABLE_COMMENT'] = $t['TABLE_COMMENT'];
  }
  $sql = 'SELECT * FROM ';
  $sql .= 'information_schema.COLUMNS ';
  $sql .= 'WHERE ';
  $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database['DB_NAME']}'";
  $fields = array();
  $field_result = mysqli_query($mysqli_conn,$sql );
  while ($t = mysqli_fetch_array($field_result))
  {
    $fields[] = $t;
  }
  $tables[$k]['COLUMN'] = $fields;
}
mysqli_close($mysqli_conn);
$html = '';
// 循环所有表
//print_r($tables);
foreach($tables as $k => $v)
{
  $html .= '<table border="1" cellspacing="0" cellpadding="0" align="center">';
  $html .= '<caption>表名：' . $v['TABLE_NAME'] . ' ' . $v['TABLE_COMMENT'] . '</caption>';
  $html .= '<tbody><tr><th>字段名</th><th>数据类型</th><th>默认值</th><th>允许非空</th><th>自动递增</th><th>备注</th></tr>';
  $html .= '';
  foreach($v['COLUMN'] AS $f)
  {
    $html .= '<td class="c1">' . $f['COLUMN_NAME'] . '</td>';
    $html .= '<td class="c2">' . $f['COLUMN_TYPE'] . '</td>';
    $html .= '<td class="c3">' . $f['COLUMN_DEFAULT'] . '</td>';
    $html .= '<td class="c4">' . $f['IS_NULLABLE'] . '</td>';
    $html .= '<td class="c5">' . ($f['EXTRA'] == 'auto_increment'?'是':' ') . '</td>';
    $html .= '<td class="c6">' . $f['COLUMN_COMMENT'] . '</td>';
    $html .= '</tr>';
  }
  $html .= '</tbody></table></p>';
}
/* 生成word */
 header ( "Content-type:application/vnd.ms-word" );
 header ( "Content-Disposition:attachment;filename={$database['DB_NAME']}数据字典.doc" );
/* 生成excel*/
//header ( "Content-type:application/vnd.ms-excel" );
//header ( "Content-Disposition:attachment;filename={$database['DB_NAME']}数据字典.xls" );
// 输出
echo '<html>
  <meta charset="utf-8">
  <title>自动生成数据字典</title>
  <style>
    body,td,th {font-family:"宋体"; font-size:12px;}
    table,h1,p{width:960px;margin:0px auto;}
    table{border-collapse:collapse;border:1px solid #CCC;background:#efefef;}
    table caption{text-align:left; background-color:#fff; line-height:2em; font-size:14px; font-weight:bold; }
    table th{text-align:left; font-weight:bold;height:26px; line-height:26px; font-size:12px; border:1px solid #CCC;padding-left:5px;}
    table td{height:20px; font-size:12px; border:1px solid #CCC;background-color:#fff;padding-left:5px;}
    .c1{ width: 150px;}
    .c2{ width: 150px;}
    .c3{ width: 80px;}
    .c4{ width: 100px;}
    .c5{ width: 100px;}
    .c6{ width: 300px;}
  </style>
  <body>';
echo '<h1 style="text-align:center;">'.$database['DB_NAME'].'数据字典</h1>';
echo '<p style="text-align:center;margin:20px auto;">生成时间：' . date('Y-m-d H:i:s') . '</p>';
echo $html;
echo '<p style="text-align:left;margin:20px auto;">总共：' . count($tables) . '个数据表</p>';
echo '</body></html>';
?>
