@extends('front.layout.front')
@section('content')
    <div class="clearfix"></div>
    <div class="main-flex">
        <div class="main-content inner_content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="panel panel-default panel-heading-space">
                            <div class="panel-heading">Terms & Condition</div>
                            <div class="panel-body">
                                <p>This Agreement is a contract between you and {{get_config('site_url')}} and governs
                                    your use of all
                                    {{get_config('site_name')}} Services. By signing up to use an account
                                    through {{get_config('site_url')}}, or any
                                    associated websites, APIs, or mobile applications (collectively the
                                    "{{get_config('site_name')}} Site"),
                                    you agree that you have read, understood, and accept all of the terms and conditions
                                    contained in this Agreement, as well as our Privacy Policy.</p>
                                <p>Using the {{get_config('site_name')}} Services means that you must accept all of the
                                    terms and conditions
                                    mentioned herein below. You should read all of these terms carefully.</p>
                                <h5 class="blue-color">Eligibility</h5>
                                <p>To be eligible to use the {{get_config('site_name')}} Services, you must be at least
                                    18 years old.</p>
                                <h5 class="blue-color">{{get_config('site_name')}} Services</h5>
                                <p>Your {{get_config('site_name')}} account ("{{get_config('site_name')}} Account")
                                    encompasses the following basic {{get_config('site_name')}}
                                    services:</p>
                                <ol>
                                    <li>One or more hosted Digital Currency wallets that allow users to store certain
                                        supported digital currencies, like Bitcoin or Ethereum ("Digital Currency"), and
                                        to track, transfer, and manage their supported Digital Currencies (the "Hosted
                                        Digital Currency Wallet");
                                    </li>
                                    <li>Digital Currency conversion services through which users can exchange, buy and
                                        sell supported Digital Currencies in transactions
                                        with {{get_config('site_name')}} (the "Conversion
                                        Services"); and, a Digital Currency exchange platform (collectively the
                                        "{{get_config('site_name')}}
                                        Services").
                                    </li>
                                </ol>
                                <p>The risk of loss in trading or holding Digital Currency can be significant. You must
                                    carefully consider whether trading or holding Digital Currency is suitable for you
                                    in light of your financial condition.</p>
                                <h5 class="blue-color">Registration of {{get_config('site_name')}} Account</h5>
                                <p>In order to use any of the {{get_config('site_name')}} Services, you must first
                                    register by providing
                                    your name, an e-mail address, password, and affirming your acceptance of this
                                    Agreement. {{get_config('site_name')}} may, in our sole discretion, refuse to allow
                                    you to establish a
                                    {{get_config('site_name')}} Account, or limit the number
                                    of {{get_config('site_name')}} Accounts that a single user may
                                    establish and maintain at any time.</p>
                                <h5 class="blue-color">Identity Verification</h5>
                                <p>In order to use certain features of the {{get_config('site_name')}} Services,
                                    including certain transfers
                                    of Digital Currency, you may be required to provide {{get_config('site_name')}} with
                                    certain personal
                                    information, including, but not limited to, your name, address, telephone number,
                                    e-mail address, date of birth, taxpayer identification number, government
                                    identification number, and information regarding your bank account (e.g., financial
                                    institution, account type, routing number, and account number). In submitting this
                                    or any other personal information as may be required, you verify that the
                                    information is accurate and authentic, and you agree to
                                    update {{get_config('site_name')}}, if any
                                    information changes. You hereby authorize {{get_config('site_name')}} to, directly
                                    or through third
                                    parties make any inquiries we consider necessary to verify your identity and/or
                                    protect against fraud, including to query identity information contained in public
                                    reports (e.g., your name, address, past addresses, or date of birth), to query
                                    account information associated with your linked bank account (e.g., name or account
                                    balance), and to take action we reasonably deem necessary based on the results of
                                    such inquiries and reports.</p>
                                <h5 class="blue-color">Digital Currency Transactions</h5>
                                <p>{{get_config('site_name')}} processes supported Digital Currency according to the
                                    instructions received
                                    from its users and we do not guarantee the identity of any user, receiver or other
                                    party. You should verify all transaction information prior to submitting
                                    instructions to {{get_config('site_name')}}. In the event you initiate a Digital
                                    Currency Transaction by
                                    entering the recipient's email address and the recipient does not have an existing
                                    {{get_config('site_name')}} Account, {{get_config('site_name')}} will email the
                                    recipient and invite them to open an {{get_config('site_name')}}
                                    Account. If the designated recipient does not open a {{get_config('site_name')}}
                                    Account within 30 days,
                                    {{get_config('site_name')}} forfeit the supported Digital Currency associated with
                                    the. Once submitted
                                    to a Digital Currency network, a Digital Currency Transaction will be unconfirmed
                                    for a period of time pending sufficient confirmation of the transaction by the
                                    Digital Currency network. A transaction is not complete while it is in a pending
                                    state. Funds associated with transactions that are in a pending state will be
                                    designated accordingly, and will not be included in your {{get_config('site_name')}}
                                    Account balance or
                                    be available to conduct transactions. {{get_config('site_name')}} may charge network
                                    fees (miner fees)
                                    to process a Digital Currency transaction on your
                                    behalf. {{get_config('site_name')}} will calculate the
                                    network fee in its discretion; although {{get_config('site_name')}} will always
                                    notify you of the
                                    network fee at or before the time you authorize the transaction.</p>
                                <p>Buying Bitcoins or other crypto currency from {{get_config('site_name')}}account:<br>
                                    All purchases involve digital goods without a fixed value. With every payment for
                                    crypto currencies you agree with the price that {{get_config('site_name')}} have
                                    determined for you.
                                </p>
                                <p>You understand that all rates are variable and can change anytime.</p>
                                <ol>
                                    <li>You are responsible for entering the correct recipient address.</li>
                                    <li>If you entered a wrong wallet address, {{get_config('site_name')}} will not be
                                        responsible for any
                                        loss of digital currencies, arising out of this
                                        mistake. {{get_config('site_name')}} will not refund
                                        this amount, you will not receive the cryptocurrency.
                                    </li>
                                    <li>As per {{get_config('site_name')}} policy you need to make a one-time
                                        verification of your account
                                        details, if this verification has not made, No trade can be conducted
                                        on {{get_config('site_name')}}
                                    </li>
                                    <li>With every buy order, you are required to fulfill the invoice amount. This
                                        amount can be fulfilled with one of our offered payment methods. The clearing of
                                        your payment is your responsibility.
                                    </li>
                                    <li>The delivery of your crypto currencies takes place by the provision of a coin
                                        transaction to the specified coin address. We ensure you to send a successful
                                        transaction, that immediately sends to the corresponding crypto
                                        currency-network. We have no influence on the speed of the transaction, it may
                                        take up to 24 hours, and if a problem does arise then you have the right to
                                        contact our support department.
                                    </li>
                                    <li>Transactions cannot be undone by users</li>
                                    <li>If the sender address is incorrect, This error is at your own risk and we can't
                                        undo the transaction.
                                    </li>
                                    <li>When an unusual situation takes place we might hold your orders till further
                                        verification.
                                    </li>
                                    <li>On suspicion of certain transactions noticed to be incorrect/credit or debited
                                        wrongly with crypto coin or evidence of criminal
                                        activities, {{get_config('site_name')}} reserves
                                        the right to suspend such transactions and recover crypto balances. In such
                                        cases the decision taken by {{get_config('site_name')}} exchange would be final
                                        and binding to
                                        users.
                                    </li>
                                    <li>Crypto currencies ordered at {{get_config('site_name')}} cannot be refunded.
                                    </li>
                                    <li>Registered users are responsible for saving your crypto
                                        currencies. {{get_config('site_name')}}
                                        offers many tips on the website.
                                    </li>
                                    <li>{{get_config('site_name')}} crypto balance can be empty sometimes due to the big
                                        order of
                                        cryptocurrencies, please wait for your order to arrive.
                                    </li>
                                </ol>
                                <p>Selling Bitcoins or other Crypto Currency to {{get_config('site_name')}}</p>
                                <ol>
                                    <li>You can sell digital goods at {{get_config('site_name')}} Platform.</li>
                                    <li>For every application, {{get_config('site_name')}} will generate a unique
                                        address for your order.
                                        After we receive the payment we will carry out the transaction
                                        (weekends/holidays excluded).
                                    </li>
                                    <li>You must deposit the correct amount of cryptocurrency at the address given by
                                        {{get_config('site_name')}}. Deposit your cryptocurrencies within 15 minutes and
                                        the transaction
                                        must have at least 1 confirmation by the blockchain within 15 minutes. If we do
                                        not receive the correct amount within 15 minutes OR within the confirmation time
                                        limit, {{get_config('site_name')}} will consider the transaction invalid
                                        and {{get_config('site_name')}} will manually
                                        recalculate your order.
                                    </li>
                                    <li>You agree that no tax is deducted from the sold crypto currencies.</li>
                                    <li>You agree that the crypto currencies offered are legitimately acquired and you
                                        do not know of any possible illegal activities.
                                    </li>
                                    <li>You agree to be the owner of the account number and any other information you
                                        have given to be truthful.
                                    </li>
                                    <li>{{get_config('site_name')}} is allowed to cancel your sell orders, In such
                                        casese the crypto
                                        currencies will be paid back to the given return address.
                                    </li>
                                    <li>It is the responsibility of the vendor to provide the correct information. We
                                        carry trades almost immediately after the crypto currencies have been received
                                        at the address given by {{get_config('site_name')}}.
                                    </li>
                                    <li>Transactions are being made every business day after we have received the
                                        payment.
                                    </li>
                                </ol>
                                <h5 class="blue-color">Conversion Fees</h5>
                                <p>Each Conversion Service transaction is subject to a fee (a "Conversion Fee"). The
                                    applicable Conversion Fee is displayed to you on the {{get_config('site_name')}}Site
                                    prior to you
                                    completing a Conversion Service transaction. {{get_config('site_name')}} will not
                                    process a conversion
                                    transaction if the Conversion Fee and any other associated fees, such as wire
                                    transfer fees, would exceed the value of your transaction. Payments using other
                                    methods not described below, such as wire (if permitted), are subject to different
                                    transaction fees disclosed to you before you authorize the transaction. The
                                    availability of each Payment Method depends on a number of factors, including but
                                    not limited to your location, the identification information you have provided to
                                    us, and limitations imposed by third party payment processors.</p>
                                <h5 class="blue-color">Fees Structure</h5>
                                <table class="col-md-6">
                                    <thead>
                                    <th class="col-md-4">Currency</th>
                                    <th class="col-md-4">Deposit</th>
                                    <th class="col-md-4">Withdraw</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="col-md-4">BTC</td>
                                        <td class="col-md-4">Free</td>
                                        <td class="col-md-4">0.001 BTC</td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-4">ETH</td>
                                        <td class="col-md-4">Free</td>
                                        <td class="col-md-4">0.01 ETH</td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-4">USDT</td>
                                        <td class="col-md-4">Free</td>
                                        <td class="col-md-4">3.2 USDT</td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-4">XRP</td>
                                        <td class="col-md-4">Free</td>
                                        <td class="col-md-4">0.25 XRP</td>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td class="col-md-4">XDC</td>--}}
                                    {{--<td class="col-md-4">Free</td>--}}
                                    {{--<td class="col-md-4">Free</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                    {{--<td class="col-md-4">XDCE</td>--}}
                                    {{--<td class="col-md-4">Free</td>--}}
                                    {{--<td class="col-md-4">0.20% XDCE</td>--}}
                                    {{--</tr>--}}
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                                <h5 class="blue-color">Trading Fee:</h5>
                                <p>Buy Side : 0.50 % On Trade value. <br> Sell Side : 0.50 % on Trade value.</p>
                                <h5 class="blue-color">Anti-Money Laundering</h5>
                                <p>{{get_config('site_name')}} is committed to providing you with safe, compliant, and
                                    reputable Services.
                                    Accordingly, {{get_config('site_name')}} insists on a comprehensive and thorough
                                    customer due diligence
                                    process and implementation and ongoing analysis and reporting. This includes
                                    monitoring of and for suspicious transactions and mandatory reporting to
                                    international regulators. {{get_config('site_name')}} needs to keep certain
                                    information and
                                    documentation on file pursuant to applicable law and its contractual relationships,
                                    and {{get_config('site_name')}} hereby expressly reserves the right to keep such
                                    information and
                                    documentation. This will apply even when you terminate your relationship with
                                    {{get_config('site_name')}} or abandon your application to have an account
                                    with {{get_config('site_name')}}. {{get_config('site_name')}}
                                    reserves the right to refuse registration to, or to bar transactions from or to, or
                                    terminate any relationship with, any customer for any reason (or for no reason) at
                                    any time. In lieu of refusing registration, {{get_config('site_name')}} may perform
                                    enhanced customer
                                    due diligence procedures.</p>
                                <h5 class="blue-color">Website Accuracy</h5>
                                <p>Although, we intend to provide accurate and timely information on
                                    the {{get_config('site_name')}} Site,
                                    the {{get_config('site_name')}} Site (including, without limitation, the Content)
                                    may not always be
                                    entirely accurate, complete or current and may also include technical inaccuracies
                                    or typographical errors. In an effort to continue to provide you with as complete
                                    and accurate information as possible, information may be changed or updated from
                                    time to time without notice, including without limitation information regarding our
                                    policies, products and services. Accordingly, you should verify all information
                                    before relying on it, and all decisions based on information contained on the
                                    {{get_config('site_name')}} Site are your sole responsibility and we shall have no
                                    liability for such
                                    decisions. Links to third-party materials (including without limitation websites)
                                    may be provided as a convenience but are not controlled by us. You acknowledge and
                                    agree that we are not responsible for any aspect of the information, content, or
                                    services contained in any third-party materials or on any third party sites
                                    accessible or linked to the {{get_config('site_name')}} Site.</p>
                                <h5 class="blue-color">Suspension, Termination, and Cancellation</h5>
                                <p>{{get_config('site_name')}} may: (a) suspend, restrict, or terminate your access to
                                    any or all of the
                                    {{get_config('site_name')}} Services, and/or (b) deactivate or cancel
                                    your {{get_config('site_name')}} Account if:</p>
                                <ol>
                                    <li>We are required by a court order, or binding order of a government authority;
                                        or
                                    </li>
                                    <li>We reasonably suspect you of using your {{get_config('site_name')}} Account in
                                        connection with a
                                        prohibited use or business; or
                                    </li>
                                    <li>Use of your {{get_config('site_name')}} Account is subject to any pending
                                        litigation, investigation,
                                        or government proceeding and/or we perceive a heightened risk of legal or
                                        regulatory non-compliance associated with your Account activity; or
                                    </li>
                                    <li>Our service partners are unable to support your use; or</li>
                                    <li>You take any action that {{get_config('site_name')}} deems as
                                        circumventing {{get_config('site_name')}}'s controls,
                                        including, but not limited to, opening multiple {{get_config('site_name')}}
                                        Accounts or abusing
                                        promotions which {{get_config('site_name')}} may offer from time to time.
                                    </li>
                                    <li>You breach any of the points mentioned in the terms and conditions and the
                                        Privacy Policy
                                    </li>
                                </ol>
                                <p>If {{get_config('site_name')}} suspends or closes your account, or terminates your
                                    use of {{get_config('site_name')}}
                                    Services for any reason, we will provide you with notice of our actions unless a
                                    court order or other legal process prohibits {{get_config('site_name')}} from
                                    providing you with such
                                    notice. You acknowledge that {{get_config('site_name')}}'s decision to take certain
                                    actions, including
                                    limiting access to, suspending, or closing your account, may be based on
                                    confidential criteria that are essential to {{get_config('site_name')}}'s risk
                                    management and security
                                    protocols. You agree that {{get_config('site_name')}} is under no obligation to
                                    disclose the details of
                                    its risk management and security procedures to you.</p>
                                <p>You will be permitted to transfer Digital Currency or funds associated with your
                                    Hosted Digital Currency Wallet(s) and/or your Currency Wallet(s) for ninety (90)
                                    days after Account deactivation or cancellation unless such transfer is otherwise
                                    prohibited under the law, including but not limited to applicable sanctions
                                    programs.</p>
                                <p>You may cancel your {{get_config('site_name')}} Account at any time by withdrawing
                                    all balances and
                                    visiting <a href="http://{{url('/'}}">www.{{get_config('site_url')}}
                                        .</a> You will not be charged
                                    for canceling your {{get_config('site_name')}} Account, although you will be
                                    required to pay any
                                    outstanding amounts owed to {{get_config('site_name')}}. You authorize us to cancel
                                    or suspend any
                                    pending transactions at the time of cancellation.</p>
                                <h5 class="blue-color">Relationship of the Parties</h5>
                                <p>{{get_config('site_name')}} is an independent contractor for all purposes. Nothing in
                                    this Agreement
                                    shall be deemed or is intended to be deemed, nor shall it cause, you
                                    and {{get_config('site_name')}} to
                                    be treated as partners, joint ventures, or otherwise as joint associates for profit,
                                    or either you or {{get_config('site_name')}} to be treated as the agent of the
                                    other.</p>
                                <h5 class="blue-color">No Representations & Warranties
                                    by {{get_config('site_name')}}</h5>
                                <p>{{get_config('site_name')}} makes no representations, warranties, or guarantees to
                                    you of any kind. The
                                    Site and the Services are offered strictly on an as-is, where-is basis and, without
                                    limiting the generality of the foregoing, are offered without any representation as
                                    to merchantability or fitness for any particular purpose.</p>
                                <h5 class="blue-color">No Advice</h5>
                                <p>{{get_config('site_name')}} does not provide any investment advice or advice on
                                    trading techniques,
                                    models, algorithms, or any other schemes.</p>
                                <h5 class="blue-color">Indemnity</h5>
                                <p>The {{get_config('site_name')}} Parties shall not be liable for any act, omission,
                                    error of judgment or
                                    loss suffered by you in connection with this Agreement. You acknowledge and agree to
                                    indemnify and hold harmless the {{get_config('site_name')}} Parties from or against
                                    any or all
                                    liabilities, obligations, losses, damages, penalties, actions, judgments, suits,
                                    costs, expenses, including reasonable attorneys’ fees, rights, claims, disbursements
                                    or actions of any kind and injury (including death) arising out of or relating to
                                    your use of {{get_config('site_name')}} or our performance or nonperformance of
                                    duties.</p>
                                <h5 class="blue-color">Limitation of Liability</h5>
                                <p>IN NO EVENT SHALL {{get_config('site_name')}}, ITS AFFILIATES AND SERVICE PROVIDERS,
                                    OR ANY OF THEIR
                                    RESPECTIVE OFFICERS, DIRECTORS, AGENTS, JOINT VENTURERS, EMPLOYEES OR
                                    REPRESENTATIVES, BE LIABLE (A) FOR ANY AMOUNT THE VALUE OF THE SUPPORTED DIGITAL
                                    CURRENCY ON DEPOSIT IN YOUR {{get_config('site_name')}} ACCOUNT OR (B) FOR ANY LOST
                                    PROFITS OR ANY
                                    SPECIAL, INCIDENTAL, INDIRECT, INTANGIBLE, OR CONSEQUENTIAL DAMAGES, WHETHER BASED
                                    IN CONTRACT, TORT, NEGLIGENCE, STRICT LIABILITY, OR OTHERWISE, ARISING OUT OF OR IN
                                    CONNECTION WITH AUTHORIZED OR UNAUTHORIZED USE OF THE {{get_config('site_name')}}
                                    SITE OR THE {{get_config('site_name')}}
                                    SERVICES, OR THIS AGREEMENT, EVEN IF AN AUTHORIZED REPRESENTATIVE
                                    OF {{get_config('site_name')}} HAS
                                    BEEN ADVISED OF OR KNEW OR SHOULD HAVE KNOWN OF THE POSSIBILITY OF SUCH DAMAGES.</p>
                                <h5 class="blue-color">Force Majeure</h5>
                                <p>We shall not be liable for delays, failure in performance or interruption of service
                                    which results directly or indirectly from any cause or condition beyond our
                                    reasonable control, including but not limited to, any delay or failure due to any
                                    act of God, act of civil or military authorities, act of terrorists, civil
                                    disturbance, war, strike or other labor dispute, fire, interruption in
                                    telecommunications or Internet services or network provider services, failure of
                                    equipment and/or software, other catastrophe or any other occurrence which is beyond
                                    our reasonable control and shall not affect the validity and enforceability of any
                                    remaining provisions.</p>
                                <h5 class="blue-color">Contact {{get_config('site_name')}}</h5>
                                <p>If you have any feedback, questions, or complaints, contact us via our Customer
                                    Support webpage at <a
                                            href="http://{{url('/contact_us')}}">http://{{url('/contact_us')}}</a>
                                    , or email: <a
                                            href="mailto:{{get_config('contact_mail')}}">{{get_config('contact_mail')}}</a>
                                </p>
                                <p>When you contact us, please provide us with your name, address, Account detail and
                                    any other information we may need to identify you, your {{get_config('site_name')}}
                                    Account, and the
                                    transaction on which you have feedback, questions, or complaints.</p>
                                <h5 class="blue-color">Governing Law</h5>
                                <p>This Agreement will be exclusively governed by Japanese Laws.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection