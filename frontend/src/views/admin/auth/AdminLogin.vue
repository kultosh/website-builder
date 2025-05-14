<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5 bg-secondary-white">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                    <div class="card-body">
                        <form>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="inputEmail" type="email" v-model="email" />
                                <label for="inputEmail">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="inputPassword" type="password" v-model="password" />
                                <label for="inputPassword">Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                <button type="button" @click="handleLogin" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { login } from '../../../services/auth';

export default {
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    handleLogin() {
        login(this.email, this.password)
        .then(response => {
            const token = response.data.token;
            if (token) {
                localStorage.setItem('auth_token', token); // Store the token
                this.$router.push('/admin');               // Redirect to dashboard
            } else {
                alert('Login failed: No token returned');
            }
        })
        .catch(err => {
            console.error('Login failed:', err);
            alert('Invalid credentials');
        });

    }
  }
};
</script>
