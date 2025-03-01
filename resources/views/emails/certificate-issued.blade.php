@component('mail::message')
# Congratulations on Your AIPro Certificate!

Dear {{ $user->name }},

You have successfully earned your AIPro certification in {{ $certificate->exam->title }}.

@component('mail::button', ['url' => $certificateUrl])
View Your Certificate
@endcomponent

Best regards,
The AIPro Team
@endcomponent