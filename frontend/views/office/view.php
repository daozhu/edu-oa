<?php
$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

$staus = 1;

$stat = $model->BaiDuDocStats();
$stat = json_decode($stat, true);
if (!isset($stat['status']) || $stat['status'] != "PUBLISHED") {
    $staus = 0;
}

$share = $model->share;
if (!isset($share['status']) || $share['status'] == 0){
    $staus = 2;
}

if (!isset($_GET['code']) || !isset($share['encrypt']) || $share['encrypt'] != $_GET['code']){
    $staus = 2;
}

?>
<div class="office-view">

    <div class="text-center">
        <div class="center-block">
            <div id="reader"></div>
        </div>
    </div>

    <?php if ($staus == 1) { ?>
    <script src="http://static.bcedocument.com/reader/v2/doc_reader_v2.js"></script>
    <script type="text/javascript">
        (function () {
            var option = {
                docId: "<?= $model->docId ?>",
                token: "TOKEN",
                host: "BCEDOC",
                width: 800, // 文档容器宽度
                pn: 1, // 定位到第几页，可选
                ready: function (handler) { // 设置字体大小和颜色, 背景颜色（可设置白天黑夜模式）
                    handler.setFontSize(1);
                    handler.setBackgroundColor("#000");
                    handler.setFontColor("#fff");
                },
                flip: function (data) { // 翻页时回调函数, 可供客户进行统计等
                    console.log(data.pn);
                },
                fontSize: "big",
                toolbarConf: {
                    page: true, // 上下翻页箭头图标
                    pagenum: true, // 几分之几页
                    full: true, // 是否显示全屏图标,点击后全屏
                    copy: false, // 是否可以复制文档内容
                    position: "center" // 设置 toolbar中翻页和放大图标的位置(值有left/center)
                } //文档顶部工具条配置对象,必选
            };
            new Document("reader", option);
        })();
    </script>
    <?php } else if (0 == $staus) {?>
        <div class="text-center">
            <div class="center-block">
                <h3>正在发布处理中。。。请稍后刷新重试</h3>
            </div>
        </div>
    <?php } else if ($staus == 2) {?>
        <div class="text-center">
            <div class="center-block">
                <h3>您要查看的文件不存在</h3>
            </div>
        </div>
    <?php } ?>
</div>
