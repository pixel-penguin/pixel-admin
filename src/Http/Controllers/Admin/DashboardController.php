<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;

use PixelPenguin\Admin\Models\WebsiteDetail;
use PixelPenguin\Admin\Models\HelperClasses\AdminFeatures;

use Illuminate\Support\Facades\Route;

use Analytics;
use Spatie\Analytics\Period;

use Carbon\Carbon;

class DashboardController extends Controller
{
    private $WebsiteDetail;
	private $elapsedTime;
	
	public function index()
	{
		
		
		return view('pixel-admin::admin/dashboard/index');
	}
	
	public function analytic($startDate , $endDate){
		//echo 'hi';
		
		//public function fetchMostVisitedPages(Period $period, int $maxResults = 20): Collection;
		
		
		$startDateTime = \DateTime::createFromFormat('D M d Y H:i:s e+', $startDate);
		$EndDateTime = \DateTime::createFromFormat('D M d Y H:i:s e+', $endDate);
		
		$period = Period::create($startDateTime, $EndDateTime);
		
		
		
		
		//$period['startDate'] = \DateTime::createFromFormat('D M d Y H:i:s e+', $startDate);
		//$period['endDate'] = \DateTime::createFromFormat('D M d Y H:i:s e+', $endDate);;
		
		//$period['startDate'] = Carbon::parse($period['startDate']);
		//$period['endDate'] = Carbon::parse($period['endDate']);
		
		//$period['startDate'] = Carbon::parse($startDate);
		//$period['endDate'] = Carbon::parse($endDate);
		//dd(Period::days(7));
		
		$response = array();
		
		
		
		$visitorsAndPageViews = Analytics::fetchVisitorsAndPageViews($period);
		$topBrowsers = Analytics::fetchTopBrowsers($period);
		
		
		$browsers = array();
		foreach($topBrowsers as $browser){
			$browsers['names'][] = $browser['browser'];
			$browsers['sessions'][] = $browser['sessions'];
		}
		
		
		$visitors = array();
		$pageViews = array();		
		foreach($visitorsAndPageViews as $key => $viewsAndVisitors){
			$visitors[$key]['x'] =  date("Y-m-d", strtotime($viewsAndVisitors['date']));
			$visitors[$key]['y'] = (int)$viewsAndVisitors['visitors'];
			
			$pageViews[$key]['x'] = date("Y-m-d", strtotime($viewsAndVisitors['date']));
			$pageViews[$key]['y'] = (int)$viewsAndVisitors['pageViews'];
		}
		
		$response['success'] = true;
		$response['top_referrers'] = Analytics::fetchTopReferrers($period);
		$response['top_visited_pages'] = Analytics::fetchMostVisitedPages($period);
		
		$response['visitors'] = $visitors;
		$response['pageViews'] = $pageViews;
		$response['topBrowsers'] = $browsers;
		
		return $response;
	}
}
