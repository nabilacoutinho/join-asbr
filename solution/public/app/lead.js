var app = angular.module('leadApp', ['ngMask']);

app.controller('LeadFormController',['$http', function($http){
   
    var formController = this;
    
    formController.currentStep = 1;
    
    formController.isCurrentStep = function(step) {
        return step === formController.currentStep;
    };
    
    formController.lead = {
        id: undefined,
        name: '',
        email: '',
        phone: '',
        birthday: '',
    };
    
    formController.errors = [];
    formController.message = '';
    
    formController.saveLead = function() {
        formController.errors = [];
        $http.post('/api/leads', formController.lead)
            .then(function(response){
                if (response.data.success) {
                    
                    formController.lead.id = response.data.prospect.id;
                    formController.loadRegions();
                    formController.currentStep = 2;
                    
                } else {
                    formController.errors = response.data.errors;
                    if(formController.errors.indexOf("duplicate") !== -1) {
                        formController.currentStep = 3; // finalize this form
                        formController.message = formController.errors['duplicate'];
                    }
                }
            })
            .catch(function(error){
                console.log(error);
            });
        
    };
    
    formController.regions = [];
    formController.loadRegions = function(){
        
        $http.get('/api/regions')
            .then(function(response){
                formController.regions = response.data; // is passed as array
            })
            .catch(function(error){
                console.log(error);
            });
        
    };
    
    formController.unities = [];
    formController.loadUnity = function(){
        
        formController.unities = [];
        formController.defaultUnityOptionLabel = "Selecione sua região primeiro";
        formController.lead.unity = undefined;
        
        var url = '/api/regions/' + formController.lead.region + '/unities';
        $http.get(url)
            .then(function(response){
                formController.unities = response.data; // is passed as array
                if (formController.unities.length > 0) {
                    formController.defaultUnityOptionLabel = "Selecione a unidade mais próxima";
                } else {
                    formController.defaultUnityOptionLabel = "Não há unidades disponíveis para sua região"
                }
            })
            .catch(function(error){
                console.log(error);
            });
        
    };
    
    formController.saveRegion = function() {
        formController.errors = [];
        var url = '/api/leads/' + formController.lead.id;
        $http.post(url, formController.lead)
            .then(function(response){
                if (response.data.success) {
                    
                    formController.lead.id = response.data.prospect.id;
                    formController.currentStep = 3;
                    
                } else {
                    formController.errors = response.data.errors;
                    if(formController.errors.indexOf("duplicate") !== -1) {
                        formController.currentStep = 3; // finalize this form
                        formController.message = formController.errors['duplicate'];
                    }
                }
            })
            .catch(function(error){
                console.log(error);
            });
        
    };
    
    formController.defaultUnityOptionLabel = "Selecione sua região primeiro";
    
}]);