 document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const batchInput = document.getElementById('batch');
            
            // Add input event listeners for real-time validation
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
            
            // Batch input validation - only allow numbers
            batchInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Validate if value is within range
                if (this.value) {
                    const batchValue = parseInt(this.value);
                    if (batchValue < 1 || batchValue > 99) {
                        this.value = '';
                    }
                }
            });
            
            // Form submission handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // Validate all fields
                isValid = validateField(document.getElementById('firstName')) && isValid;
                isValid = validateField(document.getElementById('lastName')) && isValid;
                isValid = validateField(document.getElementById('section')) && isValid;
                isValid = validateField(document.getElementById('batch')) && isValid;
                
                // Validate department
                const departmentSelected = document.querySelector('input[name="department"]:checked');
                if (!departmentSelected) {
                    document.getElementById('departmentError').classList.add('show');
                    isValid = false;
                } else {
                    document.getElementById('departmentError').classList.remove('show');
                }
                
                // If form is valid, show success message
                if (isValid) {
                    document.getElementById('successMessage').style.display = 'block';
                    
                    // Reset form after 3 seconds
                    setTimeout(() => {
                        form.reset();
                        document.getElementById('successMessage').style.display = 'none';
                    }, 3000);
                }
            });
            
            // Field validation function
            function validateField(field) {
                let isValid = true;
                const errorElement = document.getElementById(field.id + 'Error');
                
                if (!errorElement) return true;
                
                switch(field.id) {
                    case 'firstName':
                    case 'lastName':
                        if (!field.value.trim() || field.value.trim().length < 2) {
                            showError(field, errorElement);
                            isValid = false;
                        } else {
                            clearError(field, errorElement);
                        }
                        break;
                        
                    case 'section':
                        if (!field.value.trim()) {
                            showError(field, errorElement);
                            isValid = false;
                        } else {
                            clearError(field, errorElement);
                        }
                        break;
                        
                    case 'batch':
                        if (!field.value || field.value < 1 || field.value > 99) {
                            showError(field, errorElement);
                            isValid = false;
                        } else {
                            clearError(field, errorElement);
                        }
                        break;
                }
                
                return isValid;
            }
            
            function showError(field, errorElement) {
                field.classList.add('error');
                errorElement.classList.add('show');
            }
            
            function clearError(field, errorElement) {
                field.classList.remove('error');
                errorElement.classList.remove('show');
            }
        });