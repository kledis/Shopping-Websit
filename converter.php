
<?php 
    $apiurl="http://svcs.eBay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=USCa584a6-618c-4389-ab93-0f0cdd0b9a9&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATAFORMAT=XML";
  // echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo $_server['query_string'];
//&keywords=harry%20potter&paginationInput.entriesPerPage=5&sortOrder=PricePlusShippingLowest";
/*echo $_POST["maxHandlingTime"];
    echo $_POST["keyWords"];
echo $_POST["resultPerPage"];
   echo $_POST["sortBy"];
echo $_POST["lowPrice"];
echo $_POST["highPrice"];
echo $_POST["new"];
echo $_POST["used"];
echo $_POST["good"];
echo $_POST["veryGood"];
echo $_POST["accepted"];*/
$pageNum=$_POST["pageNum"];
    $keyWords=$_POST["keyWords"];
    //$keyWords=str_replace(",", "%2C" ,$keyWords);
    //$keyWords=str_replace(" ", "%20" ,$keyWords);
    $keyWords = urlencode($keyWords );
    $apiurl.="&keywords=".$keyWords;
    
    $resultPer=$_POST["resultPerPage"];
    $apiurl.="&paginationInput.entriesPerPage=".$resultPer;
        
    $sortOrder=$_POST["sortBy"];        
    $apiurl.="&sortOrder=".$sortOrder;
    
    $fil=0;
    if($_POST["lowPrice"]!="")
    {
        $lowPrice=$_POST["lowPrice"];
    }
    else
    {
        $lowPrice=0;
    }
    $apiurl.="&itemFilter[$fil].name=MinPrice&itemFilter[$fil].value=".$lowPrice;
    $fil=$fil+1;
    if($_POST["highPrice"]!="")
    {
        $highPrice=$_POST["highPrice"];
        $apiurl.="&itemFilter[$fil].name=MaxPrice&itemFilter[$fil].value=".$highPrice;
        $fil=$fil+1;
    }
    else
    {
        $highPrice=-log(0);
    }
    if($_POST["new"]=="true"||$_POST["used"]=="true"||$_POST["good"]=="true"||$_POST["veryGood"]=="true"||$_POST["accepted"]=="true")
    {
        $apiurl.="&itemFilter[$fil].name=Condition";
        $subFil=0;
        if($_POST["new"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=1000";
            $subFil++;
        }
        if($_POST["used"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=3000";
            $subFil++;
        }
        if($_POST["veryGood"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=4000";
            $subFil++;
        }
        if($_POST["good"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=5000";
            $subFil++;
        }
        if($_POST["acceptable"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=6000";
            $subFil++;
        }
        $fil=$fil+1;
        
    }
    if($_POST["buyItNow"]=="true"||$_POST["auction"]=="true"||$_POST["classifiedAds"]=="true")
    {
        $apiurl.="&itemFilter[$fil].name=ListingType";
        $subFil=0;
        if($_POST["buyItNow"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=FixedPrice";
            $subFil++;
        }
        if($_POST["auction"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=Auction";
            $subFil++;
        }
        if($_POST["classifiedAds"]=="true")
        {
            $apiurl.="&itemFilter[$fil].value[$subFil]=Classified";
            $subFil++;
        }
        $fil=$fil+1;
        
    }
    if($_POST["freeShipping"]=="true")
    {
        $apiurl.="&itemFilter[$fil].name=FreeShippingOnly";
        $apiurl.="&itemFilter[$fil].value=true";
        $fil=$fil+1; 
    }
    else
    {
        $apiurl.="&itemFilter[$fil].name=FreeShippingOnly";
        $apiurl.="&itemFilter[$fil].value=false";
        $fil=$fil+1; 
    }
    if($_POST["expedited"]=="true")
    {
        $apiurl.="&itemFilter[$fil].name=ExpeditedShippingType";
        $apiurl.="&itemFilter[$fil].value=Expedited";
        $fil=$fil+1; 
    }
    if($_POST["maxHandlingTime"]!="")
    {
        $apiurl.="&itemFilter[$fil].name=MaxHandlingTime";
        $apiurl.="&itemFilter[$fil].value=".$_POST["maxHandlingTime"];
        $fil=$fil+1; 
    }
    if($_POST["returnAccepted"]=="true")
    {
        $apiurl.="&itemFilter[$fil].name=ReturnsAcceptedOnly";
        $apiurl.="&itemFilter[$fil].value=true";
        $fil=$fil+1; 
    }
    $apiurl=$apiurl."&outputSelector[0]=SellerInfo"."&outputSelector[1]=PictureURLSuperSize"."&outputSelector[2]=StoreInfo";
$apiurl=$apiurl."&paginationInput.pageNumber=".$pageNum;
    //echo $apiurl;
    //$apiurl="http://svcs.ebay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=USCa584a6-618c-4389-ab93-0f0cdd0b9a9&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATAFORMAT=XML&keywords=harry%20potter&paginationInput.entriesPerPage=5&sortOrder=PricePlusShippingLowest&itemFilter[0].name=Condition&itemFilter[0].value[0]=3000&itemFilter[0].value[1]=3000";
 
$source=$apiurl;
              
     $xml=simplexml_load_file($source); 
//$xml->addChild('fuck', 'every');
//$json = json_encode($xml);
//echo $json;


$dom=simplexml_load_file('http://second.elasticbeanstalk.com/xml.xml'); 
if($xml->ack=='Success')
{
$dom->addChild('ack','success');
$dom->addChild('resultCount',$xml->paginationOutput->totalEntries);
$dom->addChild('pageNumber',$xml->paginationOutput->pageNumber);
$dom->addChild('itemCount',$xml->paginationOutput->entriesPerPage);
$it=0;
foreach($xml->searchResult->item as $item)
{
$name='item'.$it;
$dom->addChild($name);

$dom->$name->addChild('basicInfo');
$basic=$dom->$name->basicInfo;
$basic->addChild('title',$item->title);
$basic->addChild('viewItemURL',$item->viewItemURL);
$basic->addChild('galleryURL',$item->galleryURL);
$basic->addChild('pictureURLSuperSize',$item->pictureURLSuperSize);
$basic->addChild('convertedCurrentPrice',$item->sellingStatus->convertedCurrentPrice);
$basic->addChild('shippingServiceCost',$item->shippingInfo->shippingServiceCost);
$basic->addChild('conditionDisplayName',$item->condition->conditionDisplayName);
$basic->addChild('listingType',$item->listingInfo->listingType);
$basic->addChild('location',$item->location);
$basic->addChild('categoryName',$item->primaryCategory->categoryName);
$basic->addChild('topRatedListing',$item->topRatedListing);
    
$dom->$name->addChild('sellerInfo');
$seller=$dom->$name->sellerInfo;
$seller->addChild('sellerUserName',$item->sellerInfo->sellerUserName);
$seller->addChild('feedbackScore',$item->sellerInfo->feedbackScore);
$seller->addChild('positiveFeedbackPercent',$item->sellerInfo->positiveFeedbackPercent);
$seller->addChild('feedbackRatingStar',$item->sellerInfo->feedbackRatingStar);
$seller->addChild('topRatedSeller',$item->sellerInfo->topRatedSeller);
$seller->addChild('sellerStoreName',$item->storeInfo->storeName);
$seller->addChild('sellerStoreURL',$item->storeInfo->storeURL);
    
$dom->$name->addChild('shippingInfo');
$shipping=$dom->$name->shippingInfo;
$shipping->addChild('shippingType',$item->shippingInfo->shippingType);
$loc="";
foreach($item->shippingInfo->shipToLocations as $ship){
    $loc=$loc.$ship.",";
}
//$loc2=substr($loc,-1);
$shipping->addChild('shipToLocations',$loc);
$shipping->addChild('expeditedShipping',$item->shippingInfo->expeditedShipping);
$shipping->addChild('oneDayShippingAvailable',$item->shippingInfo->oneDayShippingAvailable);
$shipping->addChild('returnsAccepted',$item->returnsAccepted);
$shipping->addChild('handlingTime',$item->shippingInfo->handlingTime);

    
$it=$it+1;
}
    
//$dom->shit->addChild('fuck', 'every');

}
else
{
    $dom->addChild('ack','No results found!');
}
//echo $apiurl;
$json = json_encode($dom);
echo $json;
/*$doc = new DOMDocument('1.0');
// create root element
$doc->addChild('shit','hehe');
$doc->addChild('papa','shabi');
echo $doc->saveXML();
$json = json_encode($doc);

 echo $json;
$doc->addChild('333');
$doc->hehe->addChild('papashaque','yes');
echo $dom->saveXML();
$json = json_encode($doc);
 echo $json;*/

?>
