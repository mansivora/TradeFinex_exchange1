<nav id="sidebar" role="navigation" data-step="2" data-intro="Template has &lt;b&gt;many navigation styles&lt;/b&gt;"
     data-position="right" class="navbar-default navbar-static-side">
    <div class="sidebar-collapse menu-scroll">
        <ul id="side-menu" class="nav">
            <li class="user-panel">
                <div class="thumb"><img src="https://cdn4.iconfinder.com/data/icons/general24/png/128/administrator.png"
                                        alt="" class="img-circle"/></div>
                <div class="info"><p>{{get_adminprofile('CMB_username')}}</p>
                    <ul class="list-inline list-unstyled">
                        <li><a href="{{url('check_admin/profile')}}" data-hover="tooltip" title="Profile"><i
                                        class="fa fa-user"></i></a></li>

                        <li><a href="{{url('check_admin/site_settings')}}" data-hover="tooltip" title="Setting"><i
                                        class="fa fa-cog"></i></a></li>
                        <li><a href="{{url('check_admin/logout')}}" data-hover="tooltip" title="Logout"><i
                                        class="fa fa-sign-out"></i></a></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </li>

            @if(Session::get('alpha_id') == 1)
                <li class="{{admin_class('home').admin_class('')}}"><a href="{{url('check_admin/home')}}"><i
                                class="fa fa-tachometer fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Dashboard</span></a></li>
                <?php
                if (admin_class('users') != '' || admin_class('userbalance') != "" || admin_class('openingbalance') != "" || admin_class('closingbalance') != "" || admin_class('kyc_users') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-user fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Manage Users</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        <li><a href="{{ url('check_admin/users') }}"
                               @if(admin_class('users')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">User List</span></a>
                        </li>
                        <li><a href="{{ url('check_admin/non_email_verified') }}"
                               @if(admin_class('userbalance')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">UnVerified Email</span></a>
                        </li>
                        <li><a href="{{ url('check_admin/userbalance') }}"
                               @if(admin_class('userbalance')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">User Balance</span></a>
                        </li>
                        {{--<li ><a href="{{ url('check_admin/users_opening_balance') }}" @if(admin_class('openingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Opening Balance</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/users_closing_balance') }}" @if(admin_class('closingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Closing Balance</span></a></li>--}}

                        <li><a href="{{ url('check_admin/kyc_users') }}"
                               @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">KYC verfication</span></a>
                        </li>
                        {{--<li ><a href="{{ url('check_admin/users_balance_validation?currency=XDC') }}" @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Balance Verification</span></a></li>--}}

                    </ul>

                </li>

                <?php
                if (admin_class('transactions') != '' || admin_class('pending_history') != "" || admin_class('trade_history') != "" || admin_class('deposit_history') != "" || admin_class('withdraw_history') != "" || admin_class('updated_history') != "" || admin_class('swap_history') != "" || admin_class('ico_history') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-exchange fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Transactions</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                    <!--  <li ><a href="{{ url('check_admin/transactions') }}" @if(admin_class('transactions')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Exchange Transactions</span></a></li> -->

                        <li><a href="{{ url('check_admin/trade_history') }}"
                               @if(admin_class('trade_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Trade Transactions</span></a></li>

                        <li><a href="{{ url('check_admin/pending_history') }}"
                               @if(admin_class('pending_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Pending Transactions</span></a></li>

                        <li><a href="{{ url('check_admin/deposit_history') }}"
                               @if(admin_class('deposit_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Deposit Transactions</span></a></li>

                        <li><a href="{{ url('check_admin/updated_history') }}"
                               @if(admin_class('updated_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Updated Transactions</span></a></li>

                        <li><a href="{{ url('check_admin/withdraw_history') }}"
                               @if(admin_class('withdraw_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">Send Transactions (Cryptocurrency)</span></a>
                        </li>

                        <li><a href="{{ url('check_admin/trade_mapping') }}"
                               @if(admin_class('trade_mapping')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Trade Mapping</span></a></li>

                        {{--<li ><a href="{{ url('check_admin/swap_history') }}" @if(admin_class('swap_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Swap Transaction</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/ico_history') }}" @if(admin_class('ico_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">ICO Transaction</span></a></li>--}}

                    </ul>

                </li>


                <li class="{{admin_class('profit')}}"><a href="{{url('check_admin/profit')}}"><i
                                class="fa fa-signal fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Admin Profit</span></a></li>

                <?php
                if (admin_class('market_price') != '' || admin_class('allprice') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-money fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Market Price</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        {{--<li><a href="{{ url('check_admin/market_price') }}"--}}
                        {{--@if(admin_class('market_price')!='')style='color:#dc6767' @endif ><i--}}
                        {{--class="fa fa-align-left"></i><span--}}
                        {{--class="submenu-title">Market Price - </span></a></li>--}}

                        <li><a href="{{ url('check_admin/allprice') }}"
                               @if(admin_class('allprice')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">All Prices</span></a>
                        </li>

                    </ul>

                </li>

                <?php
                if (admin_class('trading_fee') != '') {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-usd fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Trading Fee</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        <li><a href="{{ url('check_admin/trading_fee/ETH-USDT') }}"><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Fee ETH-USDT</span></a></li>

                        <li><a href="{{ url('check_admin/trading_fee/BTC-USDT') }}"><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Fee BTC-USDT</span></a></li>

                        <li><a href="{{ url('check_admin/trading_fee/XRP-USDT') }}"><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Fee XRP-USDT</span></a></li>

                    </ul>

                </li>

                {{--<li class="{{admin_class('fee_config')}}"><a href="{{url('check_admin/fee_config')}}"><i class="fa fa-money fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Fees / Limit Settings</span></a></li>--}}

                {{--<li class="{{admin_class('cms')}}"><a href="{{url('check_admin/cms')}}"><i class="fa fa-file-text fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Manage CMS</span></a></li>--}}

                <li class="{{admin_class('referral')}}"><a href="{{url('check_admin/referral')}}"><i
                                class="fa fa-group ">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Manage Referral</span></a></li>

                <li class="{{admin_class('user_activity')}}"><a href="{{url('check_admin/user_activity')}}"><i
                                class="fa fa-user fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">User Activity</span></a></li>


                {{--<li class="{{admin_class('faq')}}"><a href="{{url('check_admin/faq')}}"><i class="fa fa-question-circle fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Manage FAQ</span></a></li>--}}

                <li class="{{admin_class('mail_template')}}"><a href="{{url('check_admin/mail_template')}}"><i
                                class="fa fa-envelope fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Manage Email Template</span></a></li>


                {{--<li class="{{admin_class('contact_query')}}"><a href="{{url('check_admin/contact_query')}}"><i class="fa fa-phone fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Manage Enquiry</span></a></li>--}}

                {{--<li class="{{admin_class('whitelists')}}"><a href="{{url('check_admin/whitelists')}}"><i class="fa fa-ban fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Manage IP Whitelists</span></a></li>--}}

                <li class="{{admin_class('meta_content')}}"><a href="{{url('check_admin/meta_content')}}"><i
                                class="fa fa-file-text fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Manage Meta content</span></a></li>

                {{--<li class="{{admin_class('admin_details')}}"><a href="{{url('check_admin/admin_details')}}"><i class="fa fa-file-text fa-fw">--}}
                {{--<div class="icon-bg bg-orange"></div>--}}
                {{--</i><span class="menu-title">Manage Admins</span></a></li>--}}


            @elseif(Session::get('alpha_id') == 2)
                <li class="{{admin_class('home').admin_class('')}}"><a href="{{url('check_admin/home')}}"><i
                                class="fa fa-tachometer fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Dashboard</span></a></li>
                <?php
                if (admin_class('users') != '' || admin_class('userbalance') != "" || admin_class('openingbalance') != "" || admin_class('closingbalance') != "" || admin_class('kyc_users') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-user fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Manage Users</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        <li><a href="{{ url('check_admin/users') }}"
                               @if(admin_class('users')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">User List</span></a>
                        </li>

                        {{--<li ><a href="{{ url('check_admin/userbalance') }}" @if(admin_class('userbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">User Balance</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/users_opening_balance') }}" @if(admin_class('openingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Opening Balance</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/users_closing_balance') }}" @if(admin_class('closingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Closing Balance</span></a></li>--}}

                        <li><a href="{{ url('check_admin/kyc_users') }}"
                               @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">KYC verfication</span></a>
                        </li>
                        {{--<li ><a href="{{ url('check_admin/users_balance_validation?currency=XDC') }}" @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Balance Verification</span></a></li>--}}

                    </ul>

                </li>
            @elseif(Session::get('alpha_id') == 4)

                <li class="{{admin_class('home').admin_class('')}}"><a href="{{url('check_admin/home')}}"><i
                                class="fa fa-tachometer fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Dashboard</span></a></li>

                <?php
                if (admin_class('users') != '' || admin_class('userbalance') != "" || admin_class('openingbalance') != "" || admin_class('closingbalance') != "" || admin_class('kyc_users') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-user fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Manage Users</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        {{--<li ><a href="{{ url('check_admin/userbalance') }}" @if(admin_class('userbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">User Balance</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/users_opening_balance') }}" @if(admin_class('openingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Opening Balance</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/users_closing_balance') }}" @if(admin_class('closingbalance')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Closing Balance</span></a></li>--}}

                        <li><a href="{{ url('check_admin/non_email_verified') }}"
                               @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span
                                        class="submenu-title">Email Unverfied Users</span></a></li>
                        {{--<li ><a href="{{ url('check_admin/users_balance_validation?currency=XDC') }}" @if(admin_class('kyc_users')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Balance Verification</span></a></li>--}}

                    </ul>

                </li>

            @elseif(in_array(Session::get('alpha_id'),['3','5','6','7']))

                <li class="{{admin_class('home').admin_class('')}}"><a href="{{url('check_admin/home')}}"><i
                                class="fa fa-tachometer fa-fw">
                            <div class="icon-bg bg-orange"></div>
                        </i><span class="menu-title">Dashboard</span></a></li>

                <?php
                if (admin_class('transactions') != '' || admin_class('pending_history') != "" || admin_class('trade_history') != "" || admin_class('deposit_history') != "" || admin_class('withdraw_history') != "" || admin_class('updated_history') != "" || admin_class('swap_history') != "" || admin_class('ico_history') != "") {
                    $class = "active";
                    $in = 'in';
                    $style = "auto";
                } else {
                    $class = "";
                    $in = '';
                    $style = "0 px";
                }

                ?>

                <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-exchange fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Transactions</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                        {{--<!--  <li ><a href="{{ url('check_admin/transactions') }}" @if(admin_class('transactions')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Exchange Transactions</span></a></li> -->--}}

                        {{--<li ><a href="{{ url('check_admin/trade_history') }}" @if(admin_class('trade_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Trade Transactions</span></a></li>--}}

                        {{--<li ><a href="{{ url('check_admin/pending_history') }}" @if(admin_class('pending_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Pending Transactions</span></a></li>--}}

                        {{--<li ><a href="{{ url('check_admin/deposit_history') }}" @if(admin_class('deposit_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Deposit Transactions</span></a></li>--}}

                        {{--<li ><a href="{{ url('check_admin/updated_history') }}" @if(admin_class('updated_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Updated Transactions</span></a></li>--}}

                        <li><a href="{{ url('check_admin/withdraw_history') }}"
                               @if(admin_class('withdraw_history')!='')style='color:#dc6767' @endif ><i
                                        class="fa fa-align-left"></i><span class="submenu-title">Send Transactions (Cryptocurrency)</span></a>
                        </li>

                        {{--<li ><a href="{{ url('check_admin/trade_mapping') }}" @if(admin_class('trade_mapping')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Trade Mapping</span></a></li>--}}

                        {{--<li ><a href="{{ url('check_admin/swap_history') }}" @if(admin_class('swap_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">Swap Transaction</span></a></li>--}}
                        {{--<li ><a href="{{ url('check_admin/ico_history') }}" @if(admin_class('ico_history')!='')style='color:#dc6767' @endif ><i class="fa fa-align-left"></i><span class="submenu-title">ICO Transaction</span></a></li>--}}

                    </ul>

                </li>

            @endif

        </ul>
    </div>
</nav>