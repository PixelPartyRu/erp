<!-- Stored in resources/views/layouts/master.blade.php -->

<html>
    <head>
        <title>App Name - @yield('title')</title>
      	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
      	<link rel="stylesheet" type="text/css" href="/assets/css/layout.css">
      	<meta charset="UTF-8">
      	@yield('stylesheet')
      	<script type="text/javascript" src="/assets/js/jquery-2.1.4.min.js"></script>
      	<script type="text/javascript" src="/assets/js/main.js"></script>
      	<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
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
			            <li><a href="#">Начисленные комиссии</a></li>
			            <li><a href="#">Отчет о наличии оригиналов документов</a></li>
			            <li><a href="#">Лимиты</a></li>
			          </ul>
			        </li>
			      </ul>
			      <ul class="nav navbar-nav">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Операции<b class="caret"></b></a>
			          <ul class="dropdown-menu">
			            <li><a href="/delivery">Поставки</a></li>
			            <li><a href="#">Финансирование</a></li>
			            <li><a href="#">Погашение</a></li>
			            <li><a href="#">Выставление счетов</a></li>
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
			          </ul>
			        </li>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
			
            @yield('content')
        </div>
    </body>
</html>