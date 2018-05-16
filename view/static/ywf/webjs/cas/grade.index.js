$(document).ready(function() {
function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;

    myDiagram =
      $(go.Diagram, "myDiagram",  // create a Diagram for the DIV HTML element
        { initialContentAlignment: go.Spot.Center,
          layout: $(go.TreeLayout,
                    { angle: 90,
                      setsPortSpot: false,
                      setsChildPortSpot: false }) });

    myDiagram.nodeTemplate =
      $("Node", "Auto",
        $("Shape", "CreateRequest",
          { fill: "white" },
          new go.Binding("fill", "color")),
        $("TextBlock",
          { margin: 4 },
          new go.Binding("text", "key"))
      );

    myDiagram.linkTemplate =
      $("Link",
        $("Shape",
          { strokeWidth: 1.5 }),
        $("Shape",
          { toArrow: "Standard", stroke: null })
      );

    myDiagram.model =
      $(go.GraphLinksModel,
        { nodeDataArray: [
            { key: "Alpha", color: "orange" },
            { key: "Beta", color: "lightgreen" },
            { key: "Gamma", color: "lightgreen" },
            { key: "Delta", color: "pink" },
            { key: "A comment", text: "comment about Alpha", category: "Comment" },
            { key: "B comment", text: "comment about Beta", category: "Comment" },
            { key: "G comment", text: "comment about Gamma", category: "Comment" }
          ],
          linkDataArray: [
            { from: "Alpha", to: "Beta" },
            { from: "Alpha", to: "Gamma" },
            { from: "Alpha", to: "Delta" },
            { from: "A comment", to: "Alpha", category: "Comment" },
            { from: "B comment", to: "Beta", category: "Comment" },
            { from: "G comment", to: "Gamma", category: "Comment" }
          ]
        });
    myDiagram.model.undoManager.isEnabled = true;

    // show the model in JSON format
    document.getElementById("savedModel").textContent = myDiagram.model.toJson();
  }
});