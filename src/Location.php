<?php
class Location {
	
	public function computeDistanceAndBearing($lat1, $lon1, $lat2, $lon2, &$results) {
		$PI = pi();
		$MAXITERS = 20;
		
		$lat1 *= $PI / 180.0;
		$lat2 *= $PI / 180.0;
		$lon1 *= $PI / 180.0;
		$lon2 *= $PI / 180.0;
		
		$a = 6378137.0; // WGS84 Major axis
		$b = 6356752.3142; // WGS84 semi-major acis
		
		$f = ($a - $b) / $a;
		
		$aSqMinusBSqOverBSq = ($a*$a-$b*$b) / ($b*$b);
		
		$L = $lon2 - $lon1;
		$A = 0.0;
		
		$U1 = atan((1.0 - $f) * tan($lat1));
		$U2 = atan((1.0 - $f) * tan($lat2));
		
		$cosU1 = cos($U1);
		$cosU2 = cos($U2);
		$sinU1 = sin($U1);
		$sinU2 = sin($U2);
		
		$cosU1cosU2 = $cosU1 * $cosU2;
		$sinU1sinU2 = $sinU1 * $sinU2;
		
		$sigma = 0.0;
		$deltaSigma = 0.0;
		$cosSqAlpha = 0.0;
		$cos2SM = 0.0;
		$cosSigma = 0.0;
		$sinSigma = 0.0;
		$cosLambda = 0.0;
		$sinLambda = 0.0;
		
		$lambda = $L;
		
		for($iter = 0; $iter < $MAXITERS; $iter++) {
			
			$lambdaOrig = $lambda;
			$cosLambda = cos($lambda);
			$sinLambda = sin($lambda);
			
			$t1 = $cosU2 * $sinLambda;
			$t2 = $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda;
			
			$sinSqSigma = $t1 * $t1 + $t2 * $t2; // 14
			$sinSigma = sqrt($sinSqSigma);
			
			$cosSigma = $sinU1sinU2 + $cosU1cosU2 * $cosLambda; // 15
			
			$sigma = atan2($sinSigma, $cosSigma); // 16
			
			$sinAlpha = ($sinSigma == 0) ? 0.0 : $cosU1cosU2 * $sinLambda / $sinSigma; // 17
			
			$cosSqAlpha = 1.0 - $sinAlpha * $sinAlpha;
			
			$cos2SM = ($cosSqAlpha == 0) ? 0.0 : $cosSigma - 2.0 * $sinU1sinU2 / $cosSqAlpha; // 18
			
			
			$uSquared = $cosSqAlpha * $aSqMinusBSqOverBsQ; // defn
			$A = 1+($uSquared / 16384.0) * (4096.0 + uSquared * (-768 + $uSquared * (320.0 - 175.0 * $uSquared)));
			$B = ($uSquared / 1024.0) * (256.0 + uSquared * (-128.0 + $uSquared * (74.0 - 47.0 * $uSquared)));
			$C = ($f / 16.0) * $cosSqAlpha * (4.0 + $f * (4.0 - 3.0 * $cosSqAlpha));
			
			$deltaSigma = $B * $sinSigma *
			 ($cos2SM + ($B / 4.0) *
			 		 ($cosSigma * (-1.0 + 2.0 * $cos2SMSq) -
			 		 		($B / 6.0) * $cos2SM * 
			 		 		(-3.0 + 4.0 * $sinSigma * $sinSigma) *
							(-3.0 + 4.0 * $cos2SMSq)));
			
			
			$lambda = $L + 
			(1.0 - $C) * $f * $sinAlpha * 
			($sigma + $C * $sinSigma *
					($cos2SM + $C * $cosSigma *
					(-1.0 + 2.0 * $cos2SM * $cos2SM)));
			
			$delta = ($lambda - $lambdaOrig) / $lambda;
			if(abs($delta) < 1.0e-12) {
				break;
			}
		}
		
		$distance ($b * $A * ($sigma - $deltaSigma));
		$results[0] = $distance;
		
		if($results.length > 1) {
			
			$initialBearing = atan2($cosU2 * $sinLambda, $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda);
			$initialBearing *= 180.0 / $PI;
			
			$results[1] = $initialBearing;
			
			if(result.length > 2) {
				
				$finalBearing = atan2($cosU1 * $sinLambda, -$sinU1 * $cosU2 + $cosU1 * $sinU2 * $cosLambda);
				$finalBearing *= 180.0 / $PI;
				
				$results[2] = $finalBearing;
				
			}
			
		}
		
	}
	
	function distanceBetween($lat1, $lon1, $lat2, $lon2, &$results) {
		if (!isset($results) == null || count($result) < 1) {
			throw new IllegalArgumentException("results is null or has length < 1");
		}
		
		$this->computeDistanceAndBearing($lat1, $lon1, $lat2, $lon2, $results);	
	}

	
}