<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#222;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#fff;border-radius:6px;">
        <tr>
            <td style="padding:24px 28px;border-bottom:1px solid #eee;">
                <h2 style="margin:0;font-size:20px;">New inquiry from the website</h2>
                <p style="margin:6px 0 0;color:#777;font-size:13px;">{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y, H:i') }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 28px;">
                <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px;">
                    <tr><td style="color:#777;width:140px;">Name</td><td><strong>{{ $inquiry->name }}</strong></td></tr>
                    <tr><td style="color:#777;">Email</td><td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td></tr>
                    <tr><td style="color:#777;">Phone</td><td><a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a></td></tr>
                    <tr><td style="color:#777;">Interest</td><td>{{ ucfirst(str_replace('-', ' ', $inquiry->inquiry_type)) }}</td></tr>
                    <tr><td style="color:#777;">Source</td><td>{{ str_replace('_', ' ', $inquiry->source) }}</td></tr>
                    @if (!empty($inquiry->subject_title))
                        <tr><td style="color:#777;">Regarding</td><td>{{ $inquiry->subject_title }}</td></tr>
                    @endif
                </table>

                @if (!empty($inquiry->message))
                    <p style="margin:20px 0 6px;color:#777;font-size:13px;">Message</p>
                    <div style="padding:14px;background:#f8f8f8;border-radius:4px;font-size:14px;line-height:1.5;">{!! nl2br(e($inquiry->message)) !!}</div>
                @endif

                <p style="margin:24px 0 0;">
                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" style="display:inline-block;padding:10px 18px;background:#111;color:#fff;text-decoration:none;border-radius:4px;font-size:14px;">View in admin panel</a>
                </p>
                <p style="margin:16px 0 0;color:#999;font-size:12px;">Reply to this email to answer {{ $inquiry->name }} directly.</p>
            </td>
        </tr>
    </table>
</body>
</html>
