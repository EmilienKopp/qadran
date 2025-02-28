<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitReportController extends Controller
{
  private $aiService;

  public function __construct(AIService $aiService)
  {
    $this->aiService = $aiService;
  }

  public function generate(Request $request)
  {
    try {
      $response = $this->aiService->generateGitSummary($request->input('prompt'));
      Log::info('Git Report Generated: ' . json_encode($response));
      return response()->json($response);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}