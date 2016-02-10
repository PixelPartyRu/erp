<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="_token" content="{!! csrf_token() !!}">
		<title>TAG.Factor - @yield('title')</title>
		<link rel="apple-touch-icon" href="/favicon.png">
      	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-select.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/layout.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-editable.css">

      	
      	@yield('stylesheet')
      	<script type="text/javascript" src="/assets/js/jquery-2.1.4.min.js"></script>
      	<script type="text/javascript" src="/assets/js/main.js"></script>
      	<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
      	<script type="text/javascript" src="/assets/js/bootstrap-select.js"></script>
      	<script type="text/javascript" src="/assets/js/layout.js"></script>
      	<script type="text/javascript" src="/assets/js/bootstrap-editable/bootstrap-editable.js"></script>

      	@yield('javascript')
    </head>
    <body>
        <div class="container-fluid">
        	<nav class="navbar navbar-default" role="navigation">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-dropdown-menu">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/assets/img/logo-color.jpg" alt="ФакторФакторинг">
                </a>
			    </div>

			    <div class="collapse navbar-collapse" id="nav-dropdown-menu">
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Система<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="/tariff">Тарифные планы</a></li>
			            <li><a href="#">Шаблоны типовых документов</a></li>
			            <li><a href="#">Календарь</a></li>
			            <li><a href="#">Настройка соединения с 1С</a></li>
			            <li><a href="#">Выход</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Контрагенты<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="/client">Клиенты</a></li>
			            <li><a href="/debtor">Дебиторы</a></li>
			            <li><a href="/relation">Связи Клиент-Дебитор</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Отчеты<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="/chargeCommission">Начисленные комиссии</a></li>
			            <li><a href="#">Отчет о наличии оригиналов документов</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Операции<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="/delivery">Поставки</a></li>
			            <li><a href="/finance">Финансирование</a></li>
			            <li><a href="/repayment">Погашения</a></li>
			            <li><a href="/invoicing">Выставление счетов</a></li>
			            <li><a href="#">Документы 1С</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Риск менеджмент<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="#">АУДЗ</a></li>
			            <li><a href="#">Статистика по просрочке</a></li>
			            <li><a href="/limit">Лимиты</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    @else
                    	<li id="filter"><a href="#"><i class="fa fa-btn fa-filter"></i>Фильтр</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Выход</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
			<div class="row" id="filter-container">
				@section('filter')
		            
		        @show
			</div>
			@include('global.messages')
            @yield('content')
        </div>
        <div class="modal fade" id="DeleteAlert" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Подтверждение удаления</h4>
		        </div>
		        <div class="modal-body">
		          <p>Точно хотите удалить?</p>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-info" data-dismiss="modal">Отмена</button>
		          	{{ Form::open(array(null, 'method' => 'delete')) }}
		        		<button type="submit" class="btn btn-danger btn-mini">Удалить</button>
		    		{{ Form::close() }}
		        </div>
		      </div>
		    </div>
		</div>
		 <div class="modal fade" id="LargeModal" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title">Название окна</h4>
		        </div>
		        <div class="modal-body">
		          <p>Текст окна</p>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-info" data-dismiss="modal">Отмена</button>
		        </div>
		      </div>
		    </div>
		</div>
		<div class="container message-box">
			
		</div>
    </body>
</html>