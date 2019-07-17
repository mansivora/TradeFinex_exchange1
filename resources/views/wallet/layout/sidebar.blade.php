<nav id="sidebar" role="navigation" data-step="2" data-intro="Template has &lt;b&gt;many navigation styles&lt;/b&gt;"
     data-position="right" class="navbar-default navbar-static-side">
    <div class="sidebar-collapse menu-scroll">
        <ul id="side-menu" class="nav">
            <li class="user-panel">
                <div class="thumb"><img src="https://cdn4.iconfinder.com/data/icons/general24/png/128/administrator.png"
                                        alt="" class="img-circle"/></div>
                <div class="info"><p>Admin Wallet</p>
                    <ul class="list-inline list-unstyled">
                        <li><a href="{{url('walletjey/logout')}}" data-hover="tooltip" title="Logout"><i
                                        class="fa fa-sign-out"></i></a></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </li>

            <li class="{{admin_class('home').admin_class('')}}"><a href="{{url('walletjey/home')}}"><i
                            class="fa fa-tachometer fa-fw">
                        <div class="icon-bg bg-orange"></div>
                    </i><span class="menu-title">Dashboard</span></a></li>


            <li class="{{admin_class('profit')}}"><a href="{{url('walletjey/profit')}}"><i class="fa fa-signal fa-fw">
                        <div class="icon-bg bg-orange"></div>
                    </i><span class="menu-title">Admin Profit</span></a></li>

            <?php
            if (admin_class('walletdeposit') != '') {
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
                    </i><span class="menu-title">Wallet Deposit</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                    <li><a href="{{ url('walletjey/walletdeposit/USDT') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">USDT Deposit</span></a></li>

                    <li><a href="{{ url('walletjey/walletdeposit/ETH') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">ETH Deposit</span></a></li>

                    <li><a href="{{ url('walletjey/walletdeposit/BTC') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">BTC Deposit</span></a></li>

                    <li><a href="{{ url('walletjey/walletdeposit/XRP') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">XRP Deposit</span></a></li>

                </ul>


            </li>


            <?php
            if (admin_class('walletwithdraw') != '') {
                $class = "active";
                $in = 'in';
                $style = "auto";
            } else {
                $class = "";
                $in = '';
                $style = "0 px";
            }

            ?>

            <li class="<?php echo $class; ?>"><a href="#"><i class="fa fa-send fa-fw">
                        <div class="icon-bg bg-pink"></div>
                    </i><span class="menu-title">Wallet Transfer</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse <?php echo $in; ?>" style="height: <?php echo $style; ?>;">
                    <li><a href="{{ url('walletjey/walletwithdraw/USDT') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">USDT Transfer</span></a></li>

                    <li><a href="{{ url('walletjey/walletwithdraw/ETH') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">ETH Transfer</span></a></li>

                    <li><a href="{{ url('walletjey/walletwithdraw/BTC') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">BTC Transfer</span></a></li>

                    <li><a href="{{ url('walletjey/walletwithdraw/XRP') }}"><i class="fa fa-align-left"></i><span
                                    class="submenu-title">XRP Transfer</span></a></li>


                </ul>


            </li>


        </ul>
    </div>
</nav>