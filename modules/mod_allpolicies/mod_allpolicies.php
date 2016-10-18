<?php
    require_once dirname(__FILE__) . '/helper.php';
?>

<html>
    <head>
    
	<meta charset="utf-8">
    <?php
		defined('_JEXEC') or die;
        $document =JFactory::getDocument();
        $document->addStyleSheet('modules/mod_allpolicies/css/style.css');
	?>
	</head>
    
    <body>

        <div id="entryOverview"></div>

        <script language="javascript">

        function getEntries() {
            var recipient = "9656600697829963790L";

            jQuery("#entryOverview").html('');

			jQuery.get("http://151.248.118.224:7000/api/dapps/13479534065183842812/api/entries/list?recipientId=" + recipient, function (data) {
  
                if (data.error == "Dapp not ready") {
                    alert("Dapp offline! The master node which computes your instance of this dapp is offline. Please reload the window!");
                }

                jQuery.each(data.response.entries, function (key, value) 
                {
                    key = key + 1;
                    jQuery("#entryOverview")
                            .prepend("<div><table class='table table-bordered'><tr>"+
                            "<td style='width:5%;'><strong>#" + key + "</strong></td>"+
                            "<td  style='width:15%;'><strong>Уникальный ID:</strong> " + value.asset.uniqueID + "</td>"+
                            "<td  style='width:50%;'><strong>ФИО:</strong> " + value.asset.fio + "</td>"+
                            "<td  style='width:30%;'><strong>Паспорт:</strong> " + value.asset.passport + "</td></tr>"+
                            "</table>"+
                        "<table class='table table-bordered'><tr>"+
                        "<td style='width:30%;'><strong>Срок действия с:</strong> " + value.asset.durationFrom + "</td>" +
                        "<td style='width:30%;'><strong>Срок действия по:</strong> " + value.asset.durationTo + "</td>" +
                        "<td style='width:40%;'><strong>Цена покупки:</strong> " + value.asset.price + "</td></tr>" +
                        "<tr>" +
                        "<td colspan='3'><strong>Комментарий:</strong> " + value.asset.entry + "</td></tr></table>---</div>");
                });
            });
        }

        getEntries();

		</script>
    </body>
</html>