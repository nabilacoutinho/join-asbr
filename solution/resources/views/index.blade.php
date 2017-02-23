<!DOCTYPE html>
<html lang="pt-br" ng-app="leadApp">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Compre Já</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
        <!-- angular scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.2/angular.min.js"></script>
        <script type="text/javascript" src="app/lead.js"></script>
        
    </head>
    <body>
        <div class="container">
            <div class="row" style="margin:30px 0">
                <div class="col-lg-3">
                    <img src="img/logo.png" class="img-thumbnail">
                </div>
                <div class="col-lg-9">
                    <h3>Nome do Produto</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6" id="form-container" ng-controller="LeadFormController as formController">

                    <form id="step_1" class="form-step ng-hide" 
                          ng-show="formController.isCurrentStep(1)" ng-submit="formController.saveLead()" >
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Preencha seus dados para receber contato
                                </div>
                            </div>
                            <div class="panel-body">
                                <fieldset>
                                    <div class="row form-group">
                                        <div class="col-lg-6">
                                            <label>Nome Completo</label>
                                            <input class="form-control" type="text" name="nome" ng-model="formController.lead.name">
                                        </div>

                                        <div class="col-lg-6">
                                            <label>Data de Nascimento</label>
                                            <input class="form-control" type="text" name="data_nascimento" ng-model="formController.lead.birthday">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-lg-6">
                                            <label>Email</label>
                                            <input class="form-control" type="text" name="email" ng-model="formController.lead.email">
                                        </div>

                                        <div class="col-lg-6">
                                            <label>Telefone</label>
                                            <input class="form-control" type="text" name="telefone" ng-model="formController.lead.phone">
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-lg btn-info next-step">
                                            Próximo Passo
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>

                    <form id="step_2" class="form-step ng-hide" ng-show="formController.isCurrentStep(2)" style="/* display:none */"
                          ng-submit="formController.saveRegion()">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Preencha seus dados para receber contato
                                </div>
                            </div>
                            <div class="panel-body">
                                <fieldset>
                                    <div class="row form-group">
                                        <div class="col-lg-6">
                                            <label>Região</label>
                                            <select class="form-control" name="regiao" ng-model="formController.lead.region"
                                                    ng-change="formController.loadUnity()"
                                                     ng-options="obj.id as obj.name for (key , obj) in formController.regions track by obj.id">
                                                <option value="">Selecione a sua região</option>
                                                <!--<option>Sul</option>
                                                <option>Sudeste</option>
                                                <option>Centro-Oeste</option>
                                                <option>Nordeste</option>
                                                <option>Norte</option>-->
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label>Unidade</label>
                                            <select class="form-control" name="unidade" ng-model="formController.lead.unity"
                                                    ng-options="obj.id as obj.name for (key , obj) in formController.unities track by obj.id">
                                                <option value="">Selecione a unidade mais próxima</option>
                                                <!--<option>???</option>-->
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-lg btn-info next-step">Enviar</button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>

                    <div id="step_sucesso" class="form-step ng-hide" ng-show="formController.isCurrentStep(3)" style="/* display:none */">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Obrigado pelo cadastro!
                                </div>
                            </div>
                            <div class="panel-body">
                                Em breve você receberá uma ligação com mais informações!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h1>Chamada interessante para o produto</h1>
                    <h2>Mais uma informação relevante</h2>
                </div>
            </div>
        </div>
        <script>
            /*$(function () {
                $('.next-step').click(function (event) {
                    event.preventDefault();
                    $(this).parents('.form-step').hide().next().show();
                });
            });*/
        </script>
    </body>
</html>
