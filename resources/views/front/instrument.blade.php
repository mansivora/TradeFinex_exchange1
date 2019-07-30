@extends('front.layout.front')
@section('css')
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }
    </style>
@endsection

@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Asset Details</div>
                            <div class="panel-body">
                                <table id="customers">
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><b>Asset RefNo.:</td>
                                            <td class="text-center">ABC009286</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Tran RefNo.:</td>
                                            <td class="text-center">LBC1234474e84994848HGBVJ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Obligor Name:</td>
                                            <td class="text-center">Bank XYZ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Obligor Country:</td>
                                            <td class="text-center">Singapore</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Underlying Instrument:</td>
                                            <td class="text-center">Letter of Credit Confirmation</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Currency:</td>
                                            <td class="text-center">{{$secondCurrency}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Amount:</td>
                                            <td class="text-center">1,000,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Selldown Amount:</td>
                                            <td class="text-center">25000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Asset RefNo.:</td>
                                            <td class="text-center">ABC009286</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Maturity/Expiry Date:</td>
                                            <td class="text-center">31-09-2019</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Documents:</td>
                                            <td class="text-center"><a href="https://ipfs-gateway.xinfin.network/QmWAmGrAuPE7erE8oAFDHNVXYY678GkDj5oHf3ab418fWi" target="_blank">QmWAmGrAuPE7erE8oAFDHNVXYY678GkDj5oHf3ab418fWi&nbsp;&nbsp;<i class="fa  fa fa-download"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Seller's Retention:</td>
                                            <td class="text-center">0 %</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Applicant/Importer:</td>
                                            <td class="text-center">FGH Motors</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Original Tenor:</td>
                                            <td class="text-center">360 days</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Note on Tenor/ Expiry/ Maturity:</td>
                                            <td class="text-center">expiry beyond tenor</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Type of sale:</td>
                                            <td class="text-center">Funded</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Goods:</td>
                                            <td class="text-center">Cars</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Beneficiary/ Exporter:</td>
                                            <td class="text-center">MNO Corporation</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Transaction Status:</td>
                                            <td class="text-center">Booked</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Port of Loading:</td>
                                            <td class="text-center">Dubai</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Port of Discharge:</td>
                                            <td class="text-center">Osaka</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Sale Price:</td>
                                            <td class="text-center">12 % PA</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Disclouser Status:</td>
                                            <td class="text-center">Undisclosed</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
