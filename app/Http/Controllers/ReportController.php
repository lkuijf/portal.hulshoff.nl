<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Orders;
use App\Models\HulshoffUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function reportsPage() {
        if(!auth()->user()->is_admin || !auth()->user()->email_verified_at) return view('no-access');
        $customers = Customer::all();
        $data = [
            'clients' => $customers,
        ];
        return view('reports')->with('data', $data);
    }

    public function validateReport(Request $request) {
        $toValidate = array(
            'start' => 'required|date_format:d-m-Y',
            'end' => 'required|date_format:d-m-Y',
            'client' => 'required',
            'reportType' => 'required',
            'product' => Rule::requiredIf($request->reportType == 'stock_history'),
        );
        $validationMessages = array(
            'start.required'=> 'Please fill in the start date',
            'start.date_format'=> 'Format must be: dd-mm-yyyy',
            'end.required'=> 'Please fill in the end date',
            'end.date_format'=> 'Format must be: dd-mm-yyyy',
            'client.required'=> 'Please select a client',
            'reportType.required'=> 'Please select a report type',
            'product.required'=> 'Please select a product when generating stock history',
        );
        $validated = $request->validate($toValidate,$validationMessages);

        $request->session()->flash('message', '<p>' . __('Report is being generated') . '</p>');
        $request->session()->flash('generate_type', $request->generateType);
        return redirect()->back()->withInput();
    }

    public function generateReport(Request $req) {

            $results = false;
            if($req->reportType == 'orders' || $req->reportType == 'total_orders') {
                $resQry = DB::table('orders')
                    ->join('hulshoff_user_klantcodes', 'hulshoff_user_klantcodes.hulshoff_user_id', '=', 'orders.hulshoff_user_id')
                    ->where('hulshoff_user_klantcodes.klantCode', $req->klantCode)
                    ->where('orders.klantCode', $req->klantCode)
                    ->where('orders.is_reservation', 0)
                    ->whereBetween('orders.created_at', [date('Y-m-d', strtotime($req->startDate)) . ' 00:00:00', date('Y-m-d', strtotime($req->endDate)) . ' 00:00:00'])
                    ;
                if($req->reportType == 'total_orders') $resQry->groupBy('orders.hulshoff_user_id');
                if($req->userId) $resQry->where('hulshoff_user_klantcodes.hulshoff_user_id', $req->userId);
                $resQry->join('hulshoff_users', 'hulshoff_users.id', '=', 'hulshoff_user_klantcodes.hulshoff_user_id');
                if($req->reportType == 'orders') $resQry->select('hulshoff_users.name', 'orders.*');
                if($req->reportType == 'total_orders') $resQry->select('hulshoff_users.name', DB::raw('count(hulshoff_users.name) total'));
                $results = $resQry->get();
    
                if($req->reportType == 'orders') {
                    foreach($results as $i => $res) {
                        $resProdQry = DB::table('products')
                            ->join('order_articles', 'order_articles.product_id', '=', 'products.id')
                            ->where('order_articles.order_id', $res->id)
                            ->select('order_articles.amount', 'products.*')
                            ;
                        $productsResult = $resProdQry->get();
                        $results[$i]->products = $productsResult;
                    }
                }
            }
    
    
            if($req->reportType == 'total_products') {
                $resQry = DB::table('order_articles')
                    ->groupBy('product_id')
                    ->join('products', 'products.id', '=', 'order_articles.product_id')
                    ->join('orders', 'orders.id', '=', 'order_articles.order_id')
                    ->join('hulshoff_user_klantcodes', 'hulshoff_user_klantcodes.hulshoff_user_id', '=', 'orders.hulshoff_user_id')
                    ->where('hulshoff_user_klantcodes.klantCode', $req->klantCode)
                    ->where('orders.is_reservation', 0)
                    ->whereBetween('orders.created_at', [date('Y-m-d', strtotime($req->startDate)) . ' 00:00:00', date('Y-m-d', strtotime($req->endDate)) . ' 00:00:00'])
                    ->select('products.artikelCode', 'products.omschrijving', DB::raw('SUM(amount) as total'))
                    ;
                if($req->userId) $resQry->where('hulshoff_user_klantcodes.hulshoff_user_id', $req->userId);
    
                $results = $resQry->get();
            }


            if($req->reportType == 'stock_history') {
                $resQry = DB::table('stock_histories')
                    ->where('klantCode', $req->klantCode)
                    ->where('artikelCode', $req->artikelCode)
                    ;
                $results = $resQry->get();

                $results->periodStart = date('Y-m-d', strtotime($req->startDate));
                $results->periodEnd = date('Y-m-d', strtotime($req->endDate));
            }




            if($req->generateTypeValue =='pdf' || $req->generateTypeValue =='csv') {
                $user = false;
                $customer = Customer::where('klantCode', $req->klantCode)->first();
                if($req->userId) $user = HulshoffUser::where('id', $req->userId)->first();
                $exportData = [
                    'data' => $results,
                    'period' => $req->startDate . ' tot ' . $req->endDate,
                    'client' => $customer->naam . ' (' . $req->klantCode . ')',
                    'user' => ($user?$user->name:'-'),
                    'type' => $req->reportType,
                ];
            }




            if($req->generateTypeValue =='pdf') {
                if(!Storage::exists('pdf')) Storage::makeDirectory('pdf', 0777, true); //creates directory
                
                if($req->reportType == 'orders')            $pdf = Pdf::loadView('reports.orders-pdf', $exportData);
                if($req->reportType == 'total_orders')      $pdf = Pdf::loadView('reports.total_orders-pdf', $exportData);
                if($req->reportType == 'total_products')    $pdf = Pdf::loadView('reports.total_products-pdf', $exportData);
                if($req->reportType == 'stock_history')     $pdf = Pdf::loadView('reports.stock_history-pdf', $exportData);
                $file = $req->klantCode . '-' . $req->reportType . '-' . date('U') . '.pdf';
                $pdf->save(storage_path('app/pdf/' . $file));
                $results->export_file = '/pdf/' . $file;
            }




            if($req->generateTypeValue =='csv') {
                if(!Storage::exists('csv')) Storage::makeDirectory('csv', 0777, true); //creates directory

                $file = $req->klantCode . '-' . $req->reportType . '-' . date('U') . '.csv';
                $fp = fopen(config('filesystems.disks.csv.root') . '/' . $file, 'w');
                fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
                if($req->reportType == 'orders') {
                    $row = [];
                    $row[] = 'id';
                    $row[] = 'Order Code Klant';
                    $row[] = 'Aflever datum';
                    $row[] = 'Besteld door';
                    $row[] = 'created_at';
                    $row[] = 'updated_at';
                    $row[] = 'Bestelling';
                    fputcsv($fp, $row, ';');
                    foreach($exportData['data'] as $dataRow) {
                        $row = [];
                        $row[] = $dataRow->id;
                        $row[] = $dataRow->orderCodeKlant;
                        $row[] = $dataRow->afleverDatum;
                        $row[] = $dataRow->name;
                        $row[] = $dataRow->created_at;
                        $row[] = $dataRow->updated_at;
                        fputcsv($fp, $row, ';');
                        if(count($dataRow->products)) {
                            foreach($dataRow->products as $product) {
                                $row = [];
                                $row[] = '';
                                $row[] = '';
                                $row[] = '';
                                $row[] = '';
                                $row[] = '';
                                $row[] = '';
                                $row[] = $product->amount . 'x';
                                $row[] = $product->omschrijving . ' (' . $product->artikelCode . ')';
                                // $row[] = $product->amount . 'x ' . $product->omschrijving . ' (' . $product->artikelCode . ')';
                                fputcsv($fp, $row, ';');
                            }
                        }
                    }
                }
                if($req->reportType == 'total_orders') {
                    $row = [];
                    $row[] = 'Naam';
                    $row[] = 'Totaal orders';
                    fputcsv($fp, $row, ';');
                    foreach($exportData['data'] as $dataRow) {
                        $row = [];
                        $row[] = $dataRow->name;
                        $row[] = $dataRow->total;
                        fputcsv($fp, $row, ';');
                    }
                }
                if($req->reportType == 'total_products') {
                    $row = [];
                    $row[] = 'Artikel code';
                    $row[] = 'Artikel omschrijving';
                    $row[] = 'Totaal';
                    fputcsv($fp, $row, ';');
                    foreach($exportData['data'] as $dataRow) {
                        $row = [];
                        $row[] = $dataRow->artikelCode;
                        $row[] = $dataRow->omschrijving;
                        $row[] = $dataRow->total;
                        fputcsv($fp, $row, ';');
                    }
                }
                if($req->reportType == 'stock_history') {
                    $row = [];
                    $row[] = 'Klant code';
                    $row[] = 'Artikel code';
                    $row[] = 'Datum';
                    $row[] = 'Voorraad';
                    fputcsv($fp, $row, ';');

                    $stockByDate = [];
                    $curStock = '-';
                    $klantCode = $exportData['data'][0]->klantCode;
                    $artikelCode = $exportData['data'][0]->artikelCode;
                    foreach($exportData['data'] as $dataRow) {
                        $stockByDate[date("Y-m-d",  strtotime($dataRow->created_at))] = $dataRow->voorraad;
                    }
                    for($x=strtotime(date('Y-m-d', strtotime($req->startDate))); $x<=strtotime(date('Y-m-d', strtotime($req->endDate))); $x+=86400) {
                        if(isset($stockByDate[date("Y-m-d",  $x)])) {
                            $curStock = $stockByDate[date("Y-m-d",  $x)];
                        }
                        $row = [];
                        $row[] = $klantCode;
                        $row[] = $artikelCode;
                        $row[] = date("Y-m-d",  $x);
                        $row[] = $curStock;
                        fputcsv($fp, $row, ';');
                    }
                }
                fclose($fp);
                $results->export_file = '/csv/' . $file;
            }
// dd($results);
            if($req->reportType == 'orders') return view('reports.orders')->with('data', $results);
            if($req->reportType == 'total_orders') return view('reports.total_orders')->with('data', $results);
            if($req->reportType == 'total_products') return view('reports.total_products')->with('data', $results);
            if($req->reportType == 'stock_history') return view('reports.stock_history')->with('data', $results);

    }
}
