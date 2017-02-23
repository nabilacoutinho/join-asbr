var app = angular.module('leadApp', []);

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
        var url = '/api/regions/' + formController.lead.region + '/unities';
        $http.get(url)
            .then(function(response){
                formController.unities = response.data; // is passed as array
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
                    formController.loadRegions();
                    formController.currentStep = 3;
                    
                } else {
                    formController.errors = response.data.errors;
                    if(formController.errors.indexOf("duplicate") !== -1) {
                        formController.currentStep = 3; // finalize this form
                    }
                }
            })
            .catch(function(error){
                console.log(error);
            });
        
    };
    
}]);