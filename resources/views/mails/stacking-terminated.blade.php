<h1 style="font-align:center;">Staking Termination</h1>
<p>
    Hello {{ $stacking->username }} we already terminate your staking,
    which means we stoped your staking and we will refound your NXCC balance.

    Here is the details of your staking:
</p>
@component('mail::table')
| Balance | Terminated At| Terminated By    |
| -------- |:-------------:| --------------:|
| {{ $stacking->balance }} | {{ date('Y-m-d H:i:s') }} | SYSTEM |
@endcomponent

<br>
<br>
<br>
<p>
If you're not receive nxcc balance to your NXCC WALLET please contact us.
Thankyou.</p>