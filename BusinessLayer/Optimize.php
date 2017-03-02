<?php

    $uid = $_POST['email'];
	$name = $_POST['name'];
	$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
	$result = mysqli_query($link, "SELECT symbol FROM Stock where userID='".$uid."'");
    $symbols = [];
    while($row = $result->fetch_array())
    {
        array_push($symbols,$row['symbol']);
    }
    $adjClose = [];
    $csv=[];
    $symbols=array_unique($symbols);
    foreach($symbols as $s)
    {
        $csvIn=[];
    $result1 = mysqli_query($link, "SELECT indx FROM Indexsymbol where symbol='".$s."'");
        $row1= $result1->fetch_array();
    if($row1['indx']=='NIFTY')
    {
        $s=$s.".NS";
    }
    elseif($row1['indx']=='STI')
    {
        $s=$s.".SI";
    }
    $file = file('http://chart.finance.yahoo.com/table.csv?s='.$s.'&a=10&b=27&c=2015&d=10&e=27&f=2016&g=w&ignore=.csv');
    foreach($file as $k)
    {   
         $csvIn[]= explode(',',$k);
    }
    array_push($csv,$csvIn);
    }
    foreach($csv as $arra)
    {
        $subAdjClose=[];
        foreach($arra as $ar)    
        {        
        if($ar[6]!=$csv[0][0][6])
        {
            array_push($subAdjClose,$ar[6]);
        }
        }
        array_push($adjClose,$subAdjClose);
    }
    $expRet=[];
    foreach($adjClose as $b)
    {      $expRetSub=[];
        for($i=0;$i<52;$i++)
        {
            $expRetInd=($b[$i]/$b[$i+1])-1;
            array_push($expRetSub,$expRetInd);
        }
        array_push($expRet,$expRetSub);
    }
    $avgExpRet=[];
    foreach($expRet as $lis)
    {
        $sumExp=0;
        foreach($lis as $vals)
        {
            $sumExp+=$vals;
        }
        array_push($avgExpRet,($sumExp/51));
    }
    $covarMatrix=[];
    for($i=0;$i<count($expRet);$i++)
    {
        $covarMat=[];
        $covar=0;
        for($j=0;$j<count($expRet);$j++)
        {
            $covar=getCovariance($expRet[$i],$expRet[$j]);
            array_push($covarMat,$covar);
        }
        array_push($covarMatrix,$covarMat);
    }
    print_r($covarMatrix);
    function getCovariance( $valuesA, $valuesB )
    {
      $countA = count($valuesA);
      $countB = count($valuesB);
      if ( $countA != $countB ) {
        trigger_error( 'Arrays with different sizes: countA='. $countA .', countB='. $countB, E_USER_WARNING );
        return false;
      }

      if ( $countA < 0 ) {
        trigger_error( 'Empty arrays', E_USER_WARNING );
        return false;
      }

      // Use library function if available
      if ( function_exists( 'stats_covariance' ) ) {
        return stats_covariance( $valuesA, $valuesB );
      }

      $meanA = array_sum( $valuesA ) / floatval( $countA );
      $meanB = array_sum( $valuesB ) / floatval( $countB );
      $add = 0.0;

      for ( $pos = 0; $pos < $countA; $pos++ ) {
        $valueA = $valuesA[ $pos ];
        if ( ! is_numeric( $valueA ) ) {
          trigger_error( 'Not numerical value in array A at position '. $pos .', value='. $valueA, E_USER_WARNING );
          return false;
        }

        $valueB = $valuesB[ $pos ];
        if ( ! is_numeric( $valueB ) ) {
          trigger_error( 'Not numerical value in array B at position '. $pos .', value='. $valueB, E_USER_WARNING );
          return false;
        }

        $difA = $valueA - $meanA;
        $difB = $valueB - $meanB;
        $add += ( $difA * $difB );
      } // for

      return $add / floatval( $countA );
    }

    
    mysqli_close($link);
?>