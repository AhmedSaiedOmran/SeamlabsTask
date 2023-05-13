<?php

namespace App\Http\Controllers;

use App\CustomClasses\ApiResponseHelper;
use App\CustomClasses\TaskHelper;
use App\Rules\CalculateStepsArrayValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProblemSolvingController extends Controller
{
    public function numbers_without_5(Request $request)
    {
        // Assume that we have 512M memory limit

        $validator = Validator::make($request->all(), [
            'start_number'  => ['required', 'numeric', 'lt:end_number'],
            'end_number'    => ['required', 'numeric', 'gt:start_number'],
        ]);

        //Send failed response
        if ($validator->fails()) {
            return response()->json(
                ApiResponseHelper::createErrorResponse("Validation error in your request", $validator->messages()),
                400
            );
        }

        $validated = $validator->validated();
        $startNumber = $validated['start_number'];
        $endNumber = $validated['end_number'];

        /**
         * Technical Decisions
         * *********************
         * In this section and for handling numbers like 10^9  in effcient way
         * We need handling small range [like under 4,000,000] and numbers like [10^9]
         * So if we implement large numbers in range() function that will lead to two things:
         * First: array limit 2,147,483,647 so if the range between two numbers more than that
         * number we will have error
         *
         * Second: If we have a large elements in array that will consume all memory allocated
         * to PHP Script [assumed 512M memory allocated] and will consume a lot of time to porcess
         *
         * So [Ahmed Omran] Decicded to split implementation if less than [4,000,000] or larger
         * In Less than [4,000,000]: we will use normal range function [take less than 5 seconds]
         * If Larger than [4,000,000]: We will split them into chunks and process them
         */

        $totalNumbersElements = abs($startNumber) + abs($endNumber);

        if ($totalNumbersElements < 4000000) {
            return response()->json(
                ApiResponseHelper::createSuccessResponse("Success", TaskHelper::countNumWithRange($startNumber, $endNumber)),
                200
            );
        } else {
            return response()->json(
                ApiResponseHelper::createSuccessResponse("Success", TaskHelper::countNumWithRangeWithChunks($startNumber, $endNumber)),
                200
            );
        }
    }

    public function index_of_string(Request $request)
    {
        // Assume that we have 512M memory limit

        $validator = Validator::make($request->all(), [
            'input_string'  => ['required', 'alpha:ascii'],
        ]);

        //Send failed response
        if ($validator->fails()) {
            return response()->json(
                ApiResponseHelper::createErrorResponse("Validation error in your request", $validator->messages()),
                400
            );
        }

        $validated = $validator->validated();
        $inputString = $validated['input_string'];

        return response()->json(
            ApiResponseHelper::createSuccessResponse("Success", TaskHelper::indexOfString($inputString)),
            200
        );
    }

    public function calculate_steps(Request $request)
    {
        // Validation Of Constraints
        $validator = Validator::make($request->all(), [
            'N' => ['required', 'numeric', 'min:1', 'max:99999'],
            'Q' => ['required', new CalculateStepsArrayValidation($request->N)],
        ]);

        //Send failed response
        if ($validator->fails()) {
            return response()->json(
                ApiResponseHelper::createErrorResponse("Validation error in your request", $validator->messages()),
                400
            );
        }

        $validated = $validator->validated();

        $arrayOfElements = explode(",", $validated['Q']);
        $arraySize = $validated['N'];

        $output = [];
        for ($i = 0; $i < $arraySize; $i++) {
            $output[] = TaskHelper::calculateSteps($arrayOfElements[$i]);
        }

        return response()->json(
            ApiResponseHelper::createSuccessResponse("Success", $output),
            200
        );
    }
}
