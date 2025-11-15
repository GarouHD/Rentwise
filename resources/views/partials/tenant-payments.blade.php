{{-- resources/views/partials/tenant-payments.blade.php --}}
@if(empty($payments))
  <div class="text-slate-500">No payments yet.</div>
@else
  <ul class="space-y-2">
    @foreach ($payments as $p)
      @php
        $isOverdue = ($p['status'] ?? '') === 'overdue';
        $amount = number_format((float) ($p['amount'] ?? 0), 2);
      @endphp
      <li class="flex items-center justify-between text-sm">
        <div>
          <div class="font-medium">{{ $p['paid_on'] ?? '—' }}</div>
          <div class="text-xs text-slate-500 capitalize">
            {{ $p['status'] ?? '' }}
          </div>
        </div>
        <div class="{{ $isOverdue ? 'text-rose-600' : 'text-emerald-600' }} font-semibold">
          ${{ $amount }}
        </div>
      </li>
    @endforeach
  </ul>
@endif
