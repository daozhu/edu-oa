<?php

?>


<head>
    <title>慧润阶梯英语教育</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
        .system-message{ padding: 24px 48px;text-align: center; }
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; text-align: center;}
        .system-message .jump{ padding-top: 10px}
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
    </style>
</head>

<body class="body-white">
<div class="system-message">
    <h1>&gt;_&lt;</h1>
    <p class="error">姓名不能为空！</p>
    <p class="detail"></p>
    <p class="jump" style="padding-right:150px">	页面自动 <a id="href" href="javascript:history.back(-1);">跳转</a> 等待时间： <b id="wait">3</b>
    </p>
</div>

<script type="text/javascript">	(function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>