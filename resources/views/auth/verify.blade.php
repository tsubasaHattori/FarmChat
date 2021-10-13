@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('メールアドレスを認証してください。') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('仮登録完了メールを再送信しました。') }}
                        </div>
                    @endif

                    {{ __('メールアドレスの認証が済んでいません。ご登録いただいたメールアドレスにお送りした仮登録完了メールをご確認ください。') }}
                    {{ __('仮登録完了メールが届いていない場合は、') }}, <a href="{{ route('verification.resend') }}">{{ __('こちらをクリックしてください。') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
