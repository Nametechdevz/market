package com.streammarket.iptvplayer.ui.login

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.streammarket.iptvplayer.data.ApiResult
import com.streammarket.iptvplayer.data.repository.AuthRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

data class LoginUiState(
    val loading: Boolean = false,
    val error: String? = null,
    val loggedIn: Boolean = false
)

class LoginViewModel(private val authRepository: AuthRepository) : ViewModel() {

    private val _uiState = MutableStateFlow(LoginUiState())
    val uiState: StateFlow<LoginUiState> = _uiState

    fun login(serverUrl: String, username: String, password: String) {
        if (serverUrl.isBlank() || username.isBlank() || password.isBlank()) {
            _uiState.value = LoginUiState(error = "Completa todos los campos")
            return
        }

        _uiState.value = LoginUiState(loading = true)
        viewModelScope.launch {
            when (val result = authRepository.login(serverUrl.trim(), username.trim(), password)) {
                is ApiResult.Success -> _uiState.value = LoginUiState(loggedIn = true)
                is ApiResult.Error -> _uiState.value = LoginUiState(error = result.message)
            }
        }
    }
}
