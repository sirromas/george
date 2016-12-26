function ExecuteScript(strId)
{
  switch (strId)
  {
      case "6koZqq7WB1Z":
        Script1();
        break;
      case "6bwKauDNccg":
        Script2();
        break;
      case "5gh0ee6LfQ4":
        Script3();
        break;
      case "638uuvoDTrR":
        Script4();
        break;
      case "6K6I1OXkiUJ":
        Script5();
        break;
      case "6mK4wub4tqr":
        Script6();
        break;
      case "60pXxq4RdQ0":
        Script7();
        break;
      case "5o2cNiIINYl":
        Script8();
        break;
      case "6KAxqFlN7y0":
        Script9();
        break;
      case "6LmXPgEnvOH":
        Script10();
        break;
      case "6N2PHNFf1pF":
        Script11();
        break;
      case "6GLei2KHhcY":
        Script12();
        break;
      case "5sDNz4oGJ0o":
        Script13();
        break;
      case "68dqSVWiiZI":
        Script14();
        break;
      case "6BVuOlOLGok":
        Script15();
        break;
      case "6SmTJhUjlTm":
        Script16();
        break;
      case "6AeU9CbQ2LP":
        Script17();
        break;
      case "6DZozHpTD4S":
        Script18();
        break;
      case "6QphM0LScA9":
        Script19();
        break;
      case "5ezfRMgXuVw":
        Script20();
        break;
      case "6fLAsesCTk4":
        Script21();
        break;
      case "6aDbzRHi556":
        Script22();
        break;
      case "5k67C9QJiL2":
        Script23();
        break;
  }
}

//FLS Custom Code///////////////////////////////////////////////////////////////



//Configuration and declare variables
var totalPages = 23;
var currentPage = 0;
var percent = 0;
var visited_array = new Array();

//Any changes to elements
$("#control-previous").attr("style","position:absolute; left:380px;");
$("#control-next").attr("style","position:absolute; left:460px;");
$("#control-submit").attr("style","position:absolute; left:460px;");

//This stops the content area moving down when there are no buttons at the bottom 
$("#slideframe").attr("style","margin-top:12px !important; margin-left:249px !important; top: 20px !important;");

//Add the close button
$("#controls").append("<a onclick='window.close();' class='icon-finish-slide'><div id='control-close' style='display: block; position: absolute; top: 0px; left: 840px;'><div class='label CLOSE'>CLOSE</div></div></a>");

$("<style type='text/css'> .controlbar-button-close{ color:#white; font-weight:bold; background-color:black; rgba(0,0,0,.6); text-shadow:0px 1px 1px rgba(14,42,69,1); hover:pointer; font-family:Arial Black, Arial, Helvetica, sans-serif; padding:10px 20px 10px 20px;} </style>").appendTo("#control-close");
$("#control-close").addClass("controlbar-button-close");


//Show the page number text.. . 
$(".contentpane").append("<div id='customprogress' style='display: block; position: absolute; top: 544px; left: 260px;'> <span id='percenttext'>"+percent+"</span>% Completed - Page <span id='currentpagetext'>"+currentPage+"</span> of "+totalPages+"</div>");


function showPageCount(currentPage){

//Update current Page
$("#currentpagetext").html(currentPage);

}

function updateProgress(pageUpdating){




//Work out and display the percentage

var foundPage = false;

var i=0;

for(i =0; i < visited_array.length; i++){

                if(visited_array[i] == pageUpdating){
                
                foundPage = true;
                
                }
}

if(foundPage == false){
visited_array.push(i);
}


                //now do the maths
                perc = Math.round((visited_array.length / totalPages)*100);
                
                if(perc > 100){perc = 100;}
                
                $("#percenttext").html(perc);
                
}


//FLS Custom Code///////////////////////////////////////////////////////////////


function Script1()
{
  var thispage = 1;
showPageCount(thispage);
updateProgress(thispage);
}

function Script2()
{
  var thispage = 2;
showPageCount(thispage);
updateProgress(thispage);
}

function Script3()
{
  var thispage = 3;
showPageCount(thispage);
updateProgress(thispage);
}

function Script4()
{
  var thispage = 4;
showPageCount(thispage);
updateProgress(thispage);
}

function Script5()
{
  var thispage = 5;
showPageCount(thispage);
updateProgress(thispage);
}

function Script6()
{
  var thispage = 6;
showPageCount(thispage);
updateProgress(thispage);
}

function Script7()
{
  var thispage = 7;
showPageCount(thispage);
updateProgress(thispage);
}

function Script8()
{
  var thispage = 8;
showPageCount(thispage);
updateProgress(thispage);
}

function Script9()
{
  var thispage = 9;
showPageCount(thispage);
updateProgress(thispage);
}

function Script10()
{
  var thispage = 10;
showPageCount(thispage);
updateProgress(thispage);
}

function Script11()
{
  var thispage = 11;
showPageCount(thispage);
updateProgress(thispage);
}

function Script12()
{
  var thispage = 12;
showPageCount(thispage);
updateProgress(thispage);
}

function Script13()
{
  var thispage = 13;
showPageCount(thispage);
updateProgress(thispage);
}

function Script14()
{
  var thispage = 23;
showPageCount(thispage);
updateProgress(thispage);
}

function Script15()
{
  var thispage = 14;
showPageCount(thispage);
updateProgress(thispage);
}

function Script16()
{
  var thispage = 15;
showPageCount(thispage);
updateProgress(thispage);
}

function Script17()
{
  var thispage = 16;
showPageCount(thispage);
updateProgress(thispage);
}

function Script18()
{
  var thispage = 17;
showPageCount(thispage);
updateProgress(thispage);
}

function Script19()
{
  var thispage = 18;
showPageCount(thispage);
updateProgress(thispage);
}

function Script20()
{
  var thispage = 19;
showPageCount(thispage);
updateProgress(thispage);
}

function Script21()
{
  var thispage = 20;
showPageCount(thispage);
updateProgress(thispage);
}

function Script22()
{
  var thispage = 21;
showPageCount(thispage);
updateProgress(thispage);
}

function Script23()
{
  var thispage = 22;
showPageCount(thispage);
updateProgress(thispage);
}

