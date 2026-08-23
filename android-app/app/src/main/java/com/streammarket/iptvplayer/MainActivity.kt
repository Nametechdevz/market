package com.streammarket.iptvplayer

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.Surface
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import com.streammarket.iptvplayer.ui.navigation.IptvNavGraph
import com.streammarket.iptvplayer.ui.navigation.Routes
import com.streammarket.iptvplayer.ui.theme.IptvPlayerTheme

class MainActivity : ComponentActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        val app = application as IptvApp

        setContent {
            IptvPlayerTheme {
                Surface(modifier = Modifier.fillMaxSize()) {
                    AppRoot(app)
                }
            }
        }
    }
}

@Composable
private fun AppRoot(app: IptvApp) {
    var startDestination by remember { mutableStateOf<String?>(null) }

    LaunchedEffect(Unit) {
        app.authRepository.restoreSession()
        val hasSession = app.sessionManager.currentToken() != null
        startDestination = if (hasSession) Routes.HOME else Routes.LOGIN
    }

    val destination = startDestination
    if (destination == null) {
        Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
            CircularProgressIndicator()
        }
    } else {
        IptvNavGraph(app = app, startDestination = destination)
    }
}
