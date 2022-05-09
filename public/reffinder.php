<?php
ini_set('max_execution_time', '300');
error_reporting(E_ALL || ~E_NOTICE);
function drawPicture($data,$name, $id)
{
	$mydata='';
	$mycolors='';
	foreach ($data as $k=>$v)
	{
	   $mydata=$mydata."['$k', ".number_format($v,3, '.', '')."],";
	   $mycolors=$mycolors."'#81C714',";
	}
	$mydata=substr($mydata,0, strlen($mydata)-1);
	$mycolors=substr($mycolors,0, strlen($mycolors)-1);
	return "
	<div id=\"$id\"></div><script type=\"text/javascript\">
		var myData = new Array($mydata);
		var colors = [$mycolors];
		var myChart = new JSChart('$id', 'bar');
		myChart.setDataArray(myData);
		myChart.colorizeBars(colors);
		myChart.setTitle('$name');
		myChart.setTitleColor('#000000');
		myChart.setAxisNameX('<== Most stable genes   Least stable genes ==>');
		myChart.setAxisNameY('');
		myChart.setAxisColor('#000000');
		myChart.setAxisNameFontSize(12);
		myChart.setAxisNameColor('#000000');
		myChart.setAxisValuesColor('#000000');
		myChart.setBarValuesColor('#000000');
		myChart.setAxisPaddingTop(60);
		myChart.setAxisPaddingRight(140);
		myChart.setAxisPaddingLeft(150);
		myChart.setAxisPaddingBottom(40);
		myChart.setTextPaddingLeft(105);
		myChart.setTitleFontSize(12);
		myChart.setBarBorderWidth(1);
		myChart.setBarBorderColor('#C4C4C4');
		myChart.setBarSpacingRatio(50);
		myChart.setGrid(false);
		myChart.setSize(830, 321);
		myChart.setBackgroundImage('chart_bg.jpg');
		myChart.draw();
	</script>";

}

require_once 'class/reference.php';
require_once 'refereceGene/graphicReference.php';
require_once 'class/class.Numerical.php';
require_once 'class/MyStatistics.php';

$okay = "hBAct	hGAPDH	hSDHA	hTBCA	hTUBA1A	hRNU44	hU6	hRNU48	hRNU47	h18s\n19.3112	22.28325	24.8479	22.9217	24.7194	17.5574	14.46205	19.4794	16.4062	18.99305\n19.16265	22.63935	24.93535	22.8954	24.7734	17.58445	14.4329	19.5376	16.4733	19.33055\n19.14815	22.3895	24.56275	22.51135	24.4619	17.93015	14.4635	19.73925	16.5504	19.5449\n21.81065	24.6102	26.5362	23.36915	27.01725	18.1465	14.4691	20.0296	17.003	19.4468\n21.1704	24.0964	26.02375	23.5005	26.0287	17.7986	15.0001	19.6619	16.43175	19.5778\n23.4701	25.95015	27.0499	24.54845	28.30655	18.60915	16.04265	20.5171	17.3307	20.03305\n19.27045	23.49115	25.0835	22.84805	24.67245	17.7206	14.336	19.8189	16.5204	19.30995\n19.0253	22.8714	24.69045	22.7619	24.47635	17.8875	14.47215	19.87185	16.61655	20.05875\n19.16015	22.9632	24.68925	22.5935	24.49845	18.026	14.72145	19.98605	16.76375	20.56225\n20.23935	24.2292	25.4872	23.1425	25.45795	17.62315	14.73475	19.68395	16.3622	20.12155\n20.6476	23.9726	25.84975	23.4667	25.92005	17.91115	15.0755	19.7871	16.47465	20.0937\n22.8857	26.0722	27.2926	24.5212	27.9778	17.6749	15.2755	19.76915	16.386	20.35435\n19.96615	22.7419	25.27745	22.9304	25.04025	18.04825	14.99655	20.29905	16.9748	20.3836\n20.0786	22.61245	25.4461	22.79935	24.9942	17.74855	14.5316	20.155	16.67935	20.22445\n20.7771	23.82425	25.7362	22.70535	25.11675	16.88815	13.50115	19.1055	15.6059	18.39635\n21.58675	23.7839	26.3449	23.28645	26.0738	18.09565	15.0952	20.4421	17.02225	20.12955\n22.15435	24.16015	26.665	23.533	26.52845	17.21855	14.51215	19.70135	16.02825	18.68725\n24.07285	26.44245	27.4036	24.6452	29.01625	18.28	15.592	20.3794	16.7971	20.24645";
$data=trim($_POST["data"]);		 			

if($data !="")
{ 
	$Reference=new ReferenceGenes($data);
	asort($Reference->referenceAvg);
	asort($Reference->bkFinalIndex);	
	asort($Reference->normfinderResult);	
	asort($Reference->genormResult);
	//Added by Ivo Pontes
    $finalGenesOrder = array();
    
	$html = "<table width=90%  id='deltaCT'><tr align='center'><td colspan='".($Reference->genesNumber+1)."'><b>Ranking Order (Better--Good--Average)</b></td> <tr><td>Method</td>";
	$sum=1;
	for($gene =0; $gene< $Reference->genesNumber;$gene++)
	{
	  $html .= "<td>".$sum++."</td>";
	}   
	$html .= "</tr>";
	
	$html .= '<tr><td> <a href="#" id="detactAnchor">Delta CT</a></td>';
   foreach ($Reference->referenceAvg as $k=>$v)
   { 
     $html .= "<td>".$k."</td>";
   }	
	$html .= "</tr>";
	
	$html .= '<tr><td> <a href="#" id="bestkeeperAnchor">  BestKeeper </a></td>';
   foreach ($Reference->bkFinalIndex as $k=>$v)
   { 
     $html .= "<td>".$k."</td>";
   }	
	$html .= "</tr>";	
		
		
	$html .= '<tr><td> <a href="#" id="normdetect">Normfinder</a></td>';
   foreach ($Reference->normfinderResult as $k=>$v)
   { 
     $html .= "<td>".$k."</td>";
   }	
	$html .= "</tr>";	
	

	$html .= '<tr><td> <a href="#" id="Genormdetect">Genorm</a></td>';
	$sum=0;
   foreach ($Reference->genormResult as $k=>$v)
   { 
    $sum++;
    if($sum==2)
	{
	  $html .=   "<td> </td> <td>".$k."</td>";
	}
	else
	{
     $html .= "<td>".$k."</td>";
	}
   }	
	$html .= "</tr>";	

	$finalGensRank22=array();

	$sum=0;
	foreach ($Reference->referenceAvg as $k=>$v) 
	{
	  $sum++;
	  $finalGensRank22[$k]=array();
	  array_push($finalGensRank22[$k], $sum);
	}
    $sum=0;
	foreach ($Reference->bkFinalIndex as $k=>$v) 
	{
	  $sum++;
	  array_push($finalGensRank22[$k], $sum);
	}

	$sum=0;
	foreach ($Reference->normfinderResult as $k=>$v) 
	{
	  $sum++;
	  array_push($finalGensRank22[$k], $sum);
	}	
	
	$sum=0;	
	foreach ($Reference->genormResult as $k=>$v) 
	{
	  $sum++;	  
	  if($sum==1)
	  {
	    #hRNU44 | hRNU47
	    $firstTwoGene= preg_split ("/\s+\|\s+/", trim($k));
		array_push($finalGensRank22[$firstTwoGene[0]], $sum);
		array_push($finalGensRank22[$firstTwoGene[1]], $sum);
		$sum++;
	  }
	  else
	  {	  
	     array_push($finalGensRank22[$k], $sum);
	  }
	}
	
	
	//get geomean
    foreach ($Reference->referenceAvg as $k=>$v) 
	{
     $finalGensRank22[$k]=LeonStat::GEOMEAN($finalGensRank22[$k]);
	}	
	
	//last ranking
	asort($finalGensRank22);	
	$html .= '<tr style="background-color:#D5F5FF;font-weight:bold"><td> <a href="#" id="comprehensiveRank">Recommended comprehensive ranking</a></td>';
	$displayFinalRnak=array();
	$displayFinalRnak=$finalGensRank22;


    foreach ($finalGensRank22 as $k=>$v) 
	{
       $html .=   "<td>".$k."</td>";
	   //Added by Ivo Pontes
	   array_push($finalGenesOrder,$k);
	}
	$html .= "</tr>";	
	$html .= "</table>";
	
		
   $html .= '<div id="tabs">
	  <ul> 	  
	    <li><a href="#tabs-1">Comprehensive Ranking</a></li>  
		<li><a href="#tabs-2">Delta CT</a></li>
		<li><a href="#tabs-3">BestKeeper</a></li>
		<li><a href="#tabs-4">Normfinder</a></li>
		<li><a href="#tabs-5">Genorm</a></li>
		
	</ul>';
	
 	$html .= '<div id="tabs-1">';
	$html .= "<table id='comRNAking' width='30%'>    
	<tr><td align='right'>Genes</td><td align='left' >Geomean of ranking values</td></tr>";   
	foreach ($displayFinalRnak as $k=>$v) 
	{
       $html .= "<tr><td align='right'>$k </td><td align='left' >".number_format($v,2, '.', '')."</td></tr>"; 
	}
	$html .= "</tr>";	
	$html .= "</table>";
	 $html .= drawPicture($displayFinalRnak, "Comprehensive gene stability","containerdeltComprehensive");
	$html .= '</div>';	
	$html .= '<div id="tabs-2">';
	$html .= "<table id='deltaCT' width='30%'> 
	<tr><td align='right'>Genes</td><td align='left' >Average of STDEV</td></tr>";   
	foreach ($Reference->referenceAvg as $k=>$v)
	{ 
	 $html .= "<tr><td align='right'>$k  </td><td align='left' >".number_format($v,2, '.', '')."</td></tr>"; 
	}
	$html .= "</table><br/>";	
	
	$html .= drawPicture($Reference->referenceAvg, "Gene stability by Delta CT method","containerdeltCT");
	$html .= '</div>';

	//report best keeper
	$html .= '<div id="tabs-3">';
	$html .= $Reference->reportBestKeeper();  
    $html .= drawPicture($Reference->bkFinalIndex, "Gene stability by BestKeeper","containerdeltBestKeeper");	
	$html .= '</div>'; 	

	//normfinder
	$html .= '<div id="tabs-4">';
	$html .= "<table id='normfinderTable' >
	 
	<tr><td>Gene name</td><td>Stability value</td><tr/>";
    foreach ($Reference->normfinderResult as $k=>$v)
    { 
     $html .= "<tr><td>".$k."</td>"."<td>".number_format($v,3, '.', '')."</td></tr>";
    }	 
	$html .= "</table>";
	$html .= drawPicture($Reference->normfinderResult, "Gene stability by normFinder","containerNormfinder");
 
	$html .= '</div>'; 	
  
    //genorm
	$html .= '<div id="tabs-5">';
	$html .= "<table id='GenormdetectTable' >
	 
	<tr><td>Gene name</td><td>Stability value</td><tr/>";
	foreach ($Reference->genormResult as $k=>$v)
	{ 
	 $html .= "<tr><td>".$k."</td>"."<td>".number_format($v,3, '.', '')."</td></tr>";
	}	

	$html .= "</table>";
	$html .= drawPicture($Reference->genormResult, "Gene stability by Genorm","containerdeltgenorm");
	$html .= '</div>'; 	
	
    $response = array(
        'status' => true,
        'message' => 'Success',
        'data' => $html
    );

    header('Content-Type: application/json');
    echo json_encode(['genes' => $finalGenesOrder,'html' => $html]);
    //echo json_encode($html);
    //header('Content-Type: plain/text');
    //echo $html;
}	
