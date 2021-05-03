<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use function MongoDB\BSON\toJSON;

class CurrencyController extends Controller
{
    public function getCurrencies(): JsonResponse
    {
        $currencies = $this->requestCurrencies();

        $formattedCurrencyObjects = [];
        foreach ($currencies as $currency => $_) {
            array_push($formattedCurrencyObjects, strtoupper($currency));
        }

        return response()->json($formattedCurrencyObjects);
    }

    public function convertCurrencies(Request $request): JsonResponse
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        if (!$request->has('amount') || !$request->has('baseCurrency') || !$request->has('currenciesToConvert')) {
            $out->writeln('Missing a required field');
            // TODO: return error code with message
        }

        $currencyNameLibrary = $this->requestCurrencies();

        // get the base currency value passed in
        $baseCurrency = strtolower($request->get('baseCurrency'));
        // request the currencies exchange rates for the base currency value
        $response = Http::get('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/' . $baseCurrency . '.json');
        $responseJson = $response->json();
        $exchangeRatesForBase = $responseJson[$baseCurrency];

        $currenciesToConvert = explode(',', $request->get('currenciesToConvert'));

        // now loop through, find the passed in currencies to convert to and build a response object
        $responseObject = [];
        foreach ($currenciesToConvert as $currencyToConvert) {
            $rate = $exchangeRatesForBase[strtolower($currencyToConvert)];

            if ($rate) {
                $itemDetails = (object)[];
                $itemDetails->baseCode = strtoupper($baseCurrency);
                $itemDetails->baseName = $currencyNameLibrary[strtolower($baseCurrency)];
                $itemDetails->amount = $request->get('amount');
                $itemDetails->code = $currencyToConvert;
                $itemDetails->rate = $rate;
                $itemDetails->convertedAmount = $rate * $request->get('amount');
                $itemDetails->name = $currencyNameLibrary[strtolower($currencyToConvert)];

                array_push($responseObject, $itemDetails);
            }
        }

        return response()->json($responseObject);
    }

    public function saveConversion(Request $request): bool
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        if (!$request->has('conversionRequest') || !$request->has('email')) {
            $out->writeln('Missing a required field');
            // TODO: return error code with message
        }

        // json encode the conversion request to store it in the DB
        $conversionRequest = json_encode($request->input('conversionRequest'));
        DB::table('user_conversions')->upsert([
            ['email' => $request->input('email'), 'conversion_request' => $conversionRequest]
        ], ['email', 'conversion_request'], ['email', 'conversion_request']);

        return true;
    }

    public function getConversions(Request $request): JsonResponse
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        if (!$request->has('email')) {
            $out->writeln('Missing a required field');
            // TODO: return error code with message
        }

        $email = $request->get('email');
        $conversions = DB::table('user_conversions')
            ->where('email', '=', $email)
            ->pluck('conversion_request')
            ->toArray();

        // The conversion_request is saved json encoded, so we have to decode it here before the entire response
        // object is json encoded.
        $conversions = array_map(fn($a) => json_decode($a), $conversions);

        return response()->json($conversions);
    }

    private function requestCurrencies(): array
    {
        $response = Http::get('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json');
        return $response->json();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
