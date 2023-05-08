<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Events</title>
</head>
<body>
    <h1>Google Calendar Events</h1>

    <ul>
        @foreach ($events as $event)
            <li>
                <h3>{{ $event->getSummary() }}</h3>
                <p>{{ $event->getDescription() }}</p>
                <p>Start: {{ $event->getStart()->dateTime ?? $event->getStart()->date }}</p>
                <p>End: {{ $event->getEnd()->dateTime ?? $event->getEnd()->date }}</p>
            </li>
        @endforeach
    </ul>
</body>
</html>
