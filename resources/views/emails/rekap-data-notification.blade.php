<h2>Rekap Data Notification</h2>
<table border="1">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->profile->phone_number ?? 'N/A' }}</td>
            <td>{{ $user->profile->address ?? 'N/A' }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
    </tbody>
</table>