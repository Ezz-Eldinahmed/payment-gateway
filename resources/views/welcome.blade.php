<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    @foreach (['danger', 'success'] as $status)
        @if (Session::has($status))
            <p class="alert alert-{{ $status }}">{{ Session::get($status) }}</p>
        @endif
    @endforeach
    <form role="form" method="POST" id="paymentForm" action="{{ route('charge') }}"
        class="max-w-md mx-auto p-6 bg-white rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Full name (on the card)</label>
            <input type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="fullName" placeholder="Full Name">
        </div>
        <div class="form-group">
            <label for="cardNumber">Card number</label>
            <div class="input-group">
                <input type="text" class="form-control" name="cardNumber" placeholder="Card Number">
                <div class="input-group-append">
                    <span class="input-group-text text-muted">
                        <i class="fab fa-cc-visa fa-lg pr-1"></i>
                        <i class="fab fa-cc-amex fa-lg pr-1"></i>
                        <i class="fab fa-cc-mastercard fa-lg"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <label><span class="hidden-xs">Expiration</span> </label>
                    <div class="input-group">
                        <select class="form-control" name="month">
                            <option value="">MM</option>
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}">{{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-control" name="year">
                            <option value="">YYYY</option>
                            @foreach (range(date('Y'), date('Y') + 10) as $year)
                                <option value="{{ $year }}">{{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label data-toggle="tooltip" title=""
                        data-original-title="3 digits code on back side of the card">CVV
                        <i class="fa fa-question-circle"></i></label>
                    <input type="number" class="form-control" placeholder="CVV" name="cvv">
                </div>
            </div>
        </div>
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit">Confirm</button>
    </form>
</body>

</html>
