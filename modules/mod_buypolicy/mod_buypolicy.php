<?php

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
 
$hello = modBuyPolicy::getDataFromLisk($params);
require JModuleHelper::getLayoutPath('mod_buypolicy');

?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Покупка полиса</title>

		<script src="modules/mod_buypolicy/js/jquery-ui.js" type="text/javascript"></script>
		<script src="modules/mod_buypolicy/js/jquery-qrcode.js" type="text/javascript"></script>
		<?php
			defined('_JEXEC') or die;
			$document =JFactory::getDocument();
			$document->addStyleSheet('modules/mod_buypolicy/css/jquery-ui.css');
		?>
	</head>
	
	<body>
		<!-- Поля для ввода-->
		<table> 
			<tr>
				<td><label for="fio">Фамилия Имя Отчество</label></td>
				<td><input type="text" id="fio" size=50></td>
			</tr>
			<tr>
				<td><label for="passport">Паспортные данные</label></td>
				<td><input type="text" id="passport"></td>
			</tr>
			<tr>
				<td><label for="from">Срок действия с</label></td>
				<td><input type="text" id="from" size=15></td>		
			</tr>
			<tr>
				<td><label for="to">Срок действия по</label></td>
				<td><input type="text" id="to" size=15></td>		
			</tr>
			<tr>
				<td><p></p><td>
			</tr>
			<tr>
				<td><label for="dif">Количество дней</label></td>
				<td><input type="text" id="dif" size=5 disabled></td>		
			</tr>
			<tr>
				<td><label for="price">Стоимость полиса</label></td>
				<td><input type="text" id="price" size=5 disabled></td>		
			</tr>
			<tr>
			<!-- Кнопка о покупке-->
				<td colspan="2" align="center"><input class="ui-button ui-widget ui-corner-all" id="buypolicy" value="Купить полис" onclick="fnBuyPolicy()"></td>
			</tr>
		</table>
	
		<div id="qrzoneLabel"> </div>
		<div id="qrzone"> </div>
	</body>

	<script>	  
		var pricePerDay = 132; /* брать информацию из базы*/
	    var dateFormat = "dd-mm-yy";
    	var recipient = "9656600697829963790L";
    	var passphrase = "guestbook";

	    var from = jQuery( "#from" ).datepicker({
	          defaultDate: "+1w",
	          changeMonth: true,
	          numberOfMonths: 2,
	          dateFormat: dateFormat
	        }).on( "change", function() {
	          to.datepicker( "option", "minDate", getDate( this ) );
			  getDif();
	        });

	    var to = jQuery( "#to" ).datepicker({
	        defaultDate: "+1w",
	        changeMonth: true,
	        numberOfMonths: 2,
	        dateFormat: dateFormat
	      })
	      .on( "change", function() {
	        from.datepicker( "option", "maxDate", getDate( this ) );
			getDif();
	      });
	 
	    function getDate( element ) {
			var date;
			try {
				date = jQuery.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}
	 
			return date;
	    }
		
		function getDif() {
			if (document.getElementById('to').value != "" &&
				document.getElementById('from').value != "") {
				document.getElementById('dif').value=(getDate(document.getElementById('to'))-
						getDate(document.getElementById('from')))/1000/60/60/24;
				document.getElementById('price').value= document.getElementById('dif').value*pricePerDay
			}
		}

		function fnBuyPolicy() {
			if (document.getElementById('price').value != "" &&
				document.getElementById('to').value != 0) {
				var todayDate = new Date();
				var uniqueID=fnGetUniqueID();
				fnInit();

				alert("Перенаправление на сайт банка для покупки\n"+
					"Дата приобретения " + todayDate + "\n" +
					"Параметры купленного полиса:\n"+
					"Уникальный идентификатор: " + uniqueID + "\n"+
					"Срок действия с: " + document.getElementById('from').value + "\n"+
					"Срок действия по: " + document.getElementById('to').value + "\n"+
					"Стоимость: " + document.getElementById('price').value);

				saveToBlockchain({
						"recipientId": recipient, 
	            		"secret": passphrase, 
	            		"entry": todayDate,  	 
						"uniqueID": uniqueID,
						"fio": document.getElementById('fio').value, 
						"passport": document.getElementById('passport').value,
						"durationFrom": document.getElementById('from').value,
						"durationTo": document.getElementById('to').value,
						"price": document.getElementById('price').value
					});

				jQuery('#qrzoneLabel').html(
					"Покупка полиса успешно завершена!<p>"+
					"Сохраните QR-код, чтобы Вы всегда могли найти свой полис:");
				jQuery('#qrzone').qrcode({
				     render: "canvas",
				     text: "ID: " +uniqueID + "\n" +
				     		"from: " + document.getElementById('from').value + "\n" +
				     		"to: " + document.getElementById('to').value,
				     width:160,
				     height:160
				});
			}
		}

		function fnInit() {
			jQuery('#qrzoneLabel').html("");
			jQuery('#qrzone').html("");
		}

		function fnGetUniqueID() {
			var uniqueID = getRandomInt(Math.pow(10,8), Math.pow(10,9)); /* Надо сделать генерацию исходя их хеша параметров*/
			return uniqueID;
		}

		function getRandomInt(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
  		}

  		function saveToBlockchain(dataToBlockchain) {
  			jQuery.ajax({
	            url: 'http://151.248.118.224:7000/api/dapps/13479534065183842812/api/entries/add',
	            type: 'PUT',
	            dataType: 'json',
	            data: dataToBlockchain
	        }).done(function (resp) {
				
	        });
  		}
	  </script>
	</html>