<?php
//vd($MODEL);

$list = $MODEL['items'];

//vd($list);

$statuses = array();


foreach($list as $order)
{
	//vd($order);
	$arr['status'][$order->orderStatus->code] ++;
	$arr['delivery'][$order->deliveryType->code]++;
	$arr['payment'][$order->paymentType->code]++;
	
}


//vd($arr);


if(date('Y-m-d') > '2016-09-24')
	die('Недоступно');
?>



<script type="text/javascript" src="/js/libs/fusioncharts-suite-xt/js/fusioncharts.js"></script>
<script type="text/javascript" src="/js/libs/fusioncharts-suite-xt/js/fusioncharts.charts.js"></script>


<div id="chart-statuses"></div>
<hr />
<div id="chart-deliveries"></div>
<hr />
<div id="chart-payments"></div>




<script>
FusionCharts.ready(function () {
    var ageGroupChart = new FusionCharts({
        type: 'pie3d',
        renderAt: 'chart-statuses',
        width: '650',
        height: '300',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": "Заказы по статусам",
                "subCaption": "",
                "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                "bgColor": "#f9f9f9",
                "showBorder": "0",
                "use3DLighting": "0",
                "showShadow": "0",
                "enableSmartLabels": "0",
                "startingAngle": "15",
                "showPercentValues": "0",
                "showPercentInTooltip": "1",
                "decimals": "1",
                "captionFontSize": "14",
                "subcaptionFontSize": "14",
                "subcaptionFontBold": "0",
                "toolTipColor": "#ffffff",
                "toolTipBorderThickness": "0",
                "toolTipBgColor": "#000000",
                "toolTipBgAlpha": "80",
                "toolTipBorderRadius": "2",
                "toolTipPadding": "5",
                "showHoverEffect":"1",
                "showLegend": "1",
                "legendBgColor": "#ffffff",
                "legendBorderAlpha": '0',
                "legendShadow": '0',
                "legendItemFontSize": '10',
                "legendItemFontColor": '#666666'
            },
            "data": [
                <?php 
                foreach($arr['status'] as $code=>$count)
                {?>
	                {
	                    "label": "<?=OrderStatus::code($code)->name?>",
	                    "value": "<?=$count?>"
	                },
                <?php 	
                }?>
                
            ]
        }
    }).render();
});




// 	доставки
FusionCharts.ready(function () {
    var ageGroupChart = new FusionCharts({
        type: 'pie3d',
        renderAt: 'chart-deliveries',
        width: '650',
        height: '300',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": "Заказы по способам доставки",
                "subCaption": "",
                "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                "bgColor": "#f9f9f9",
                "showBorder": "0",
                "use3DLighting": "0",
                "showShadow": "0",
                "enableSmartLabels": "0",
                "startingAngle": "0",
                "showPercentValues": "0",
                "showPercentInTooltip": "1",
                "decimals": "1",
                "captionFontSize": "14",
                "subcaptionFontSize": "14",
                "subcaptionFontBold": "0",
                "toolTipColor": "#ffffff",
                "toolTipBorderThickness": "0",
                "toolTipBgColor": "#000000",
                "toolTipBgAlpha": "80",
                "toolTipBorderRadius": "2",
                "toolTipPadding": "5",
                "showHoverEffect":"1",
                "showLegend": "1",
                "legendBgColor": "#ffffff",
                "legendBorderAlpha": '0',
                "legendShadow": '0',
                "legendItemFontSize": '10',
                "legendItemFontColor": '#666666'
            },
            "data": [
                <?php 
                foreach($arr['delivery'] as $code=>$count)
                {?>
	                {
	                    "label": "<?=DeliveryType::code($code)->name?>",
	                    "value": "<?=$count?>"
	                },
                <?php 	
                }?>
                
            ]
        }
    }).render();
});





//	оплаты
FusionCharts.ready(function () {
    var ageGroupChart = new FusionCharts({
        type: 'pie3d',
        renderAt: 'chart-payments',
        width: '650',
        height: '300',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": "Заказы по способам оплаты",
                "subCaption": "",
                "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                "bgColor": "#f9f9f9",
                "showBorder": "0",
                "use3DLighting": "0",
                "showShadow": "0",
                "enableSmartLabels": "0",
                "startingAngle": "0",
                "showPercentValues": "0",
                "showPercentInTooltip": "1",
                "decimals": "1",
                "captionFontSize": "14",
                "subcaptionFontSize": "14",
                "subcaptionFontBold": "0",
                "toolTipColor": "#ffffff",
                "toolTipBorderThickness": "0",
                "toolTipBgColor": "#000000",
                "toolTipBgAlpha": "80",
                "toolTipBorderRadius": "2",
                "toolTipPadding": "5",
                "showHoverEffect":"1",
                "showLegend": "1",
                "legendBgColor": "#ffffff",
                "legendBorderAlpha": '0',
                "legendShadow": '0',
                "legendItemFontSize": '10',
                "legendItemFontColor": '#666666'
            },
            "data": [
                <?php 
                foreach($arr['payment'] as $code=>$count)
                {?>
	                {
	                    "label": "<?=PaymentType::code($code)->name?>",
	                    "value": "<?=$count?>"
	                },
                <?php 	
                }?>
                
            ]
        }
    }).render();
});

</script>

