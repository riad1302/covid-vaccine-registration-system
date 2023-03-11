<x-mail::message>
# Hi {{$mailData['name']}},

Your COVID Vaccination Date is : {{$mailData['vaccination_date']}}.
Vaccine Center Name : {{$mailData['vaccine_center_name']}}.
Vaccine Center Address : {{$mailData['vaccine_center_address']}}



Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
