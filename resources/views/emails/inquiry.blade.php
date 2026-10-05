<!-- resources/views/emails/inquiry.blade.php -->

<h1>Product Inquiry</h1>

<p>Machiimpex Team,</p>

<p>You are new inquiry has been submitted with the following details:</p>

<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Name:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->name }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->email }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Mobile:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->mobile }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Quantity:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->quantity }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Product Name:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->product_name }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Product Description:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->product_description }}</td>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Product Manufacturer:</strong></td>
    <td style="padding: 8px; border: 1px solid #ddd;">{{ $inquiry->product_manufacturer }}</td>
  </tr>
</table>

<p>Please follow up with the customer promptly to provide further assistance.</p>

<p>Best regards,</p>
<p>The Machiimpex Team</p>
