<?php /* Smarty version Smarty-3.1-DEV, created on 2016-11-01 10:23:08
         compiled from "/usr/local/httpd/htdocs/php/view/cas/template/grade.index.html" */ ?>
<?php /*%%SmartyHeaderCode:1260627195580811f2df5287-53892119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29f9d7ed386f69b95737d2c7e605871c75f61db8' => 
    array (
      0 => '/usr/local/httpd/htdocs/php/view/cas/template/grade.index.html',
      1 => 1477966983,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1260627195580811f2df5287-53892119',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_580811f2e424b5_16066265',
  'variables' => 
  array (
    '_STATIC_CDN' => 0,
    'userlist' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_580811f2e424b5_16066265')) {function content_580811f2e424b5_16066265($_smarty_tpl) {?><body>
<!-- .vbox -->
<section class="vbox">

    <section>
        <section class="hbox stretch">

            <section id="content">

<title>组织架构图</title>
<meta charset="UTF-8">
<!-- Copyright 1998-2014 by Northwoods Software Corporation. -->
<link href="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/css/goSamples.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $_smarty_tpl->tpl_vars['_STATIC_CDN']->value;?>
/ywf/js/go.js"></script>
<style type="text/css">

#myOverview {
  position: absolute;
  top: 10px;
  left: 10px;
  background-color: aliceblue;
  z-index: 300; /* make sure its in front */
  border: solid 1px blue;

  width:200px;
  height:100px
}

</style>
<script id="code">

  var arr = <?php echo $_smarty_tpl->tpl_vars['userlist']->value;?>
; 
  
  function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates

    myDiagram =
      $(go.Diagram, "myDiagram",  // the DIV HTML element
        { initialDocumentSpot: go.Spot.TopCenter,
          initialViewportSpot: go.Spot.TopCenter });

    // define Converters to be used for Bindings
    function theNationFlagConverter(nation) {
      var img = new Image();
      img.src = "http://www.nwoods.com/go/Flags/" + nation.toString().toLowerCase().replace(/\s/g, "-") + "-flag.Png";
      return img;
    }

    function theInfoTextConverter(info) {
      var str = "";
      str += "业务量: " + info.money +"万";
      str += "\n\n经纪人: " + info.count+"人";
      if (typeof info.boss === "number") {
        var bossinfo = myDiagram.model.findNodeDataForKey(info.boss);
        if (bossinfo !== null) {
          str += "\nReporting to: " + bossinfo.name;
        }
      }
      return str;
    }

    // define the Node template
    myDiagram.nodeTemplate =
      $(go.Node, "Auto",
        { isShadowed: true },
        // the outer shape for the node, surrounding the Table
        $(go.Shape, "Rectangle",
          { fill: "azure" }),
        // a table to contain the different parts of the node
        $(go.Panel, "Table",
          { margin: 4, maxSize: new go.Size(140, NaN) },
          // the two TextBlocks in column 0 both stretch in width
          // but align on the left side
          $(go.RowColumnDefinition,
            { column: 0,
              stretch: go.GraphObject.Horizontal,
              alignment: go.Spot.Left }),
          // the name
          $(go.TextBlock,
            { row: 0, column: 0,
              maxSize: new go.Size(110, NaN),
              font: "bold 8pt sans-serif",
              alignment: go.Spot.Top },
            new go.Binding("text", "name")),
          // the country flag
          $(go.Picture,
            { row: 0, column: 1, margin: 2,
              desiredSize: new go.Size(34, 26),
              imageStretch: go.GraphObject.Uniform,
              alignment: go.Spot.TopRight },
            new go.Binding("element", "nation", theNationFlagConverter)),
          // the additional textual information
          $(go.TextBlock,
            { row: 1, column: 0, columnSpan: 2,
              font: "8pt sans-serif" },
            new go.Binding("text", "", theInfoTextConverter))
        )  // end Table Panel
      );  // end Node

    // define the Link template, a simple orthogonal line
    myDiagram.linkTemplate =
      $(go.Link, go.Link.Orthogonal,
        { selectable: false },
        $(go.Shape));  // the default black link shape

    // set up the nodeDataArray, describing each person/position
    var nodeDataArray = arr ;
    

    // create the Model with data for the tree, and assign to the Diagram
    myDiagram.model =
      $(go.TreeModel,
        { nodeParentKeyProperty: "boss",  // this property refers to the parent node data
          nodeDataArray: nodeDataArray });

    // create a TreeLayout
    myDiagram.layout =
      $(go.TreeLayout,
        { treeStyle: go.TreeLayout.StyleLastParents,
          angle: 90,
          layerSpacing: 80,
          alternateAngle: 0,
          alternateAlignment: go.TreeLayout.AlignmentStart,
          alternateNodeIndent: 20,
          alternateNodeIndentPastParent: 1,
          alternateNodeSpacing: 20,
          alternateLayerSpacing: 40,
          alternateLayerSpacingParentOverlap: 1,
          alternatePortSpot: new go.Spot(0, 0.999, 20, 0),
          alternateChildPortSpot: go.Spot.Left });

    // Overview
    myOverview =
      $(go.Overview, "myOverview",  // the HTML DIV element for the Overview
        { observed: myDiagram });   // tell it which Diagram to show and pan
  }
</script>
</head>
<body onload="init()">
<div id="sample" style="position: relative;">
  <div id="myDiagram" style="background-color: white; border: solid 1px black; width: 100%; height: 650px"></div>
  <div id="myOverview"></div> <!-- Styled in a <style> tag at the top of the html page -->
</div>
</body>
</html><?php }} ?>