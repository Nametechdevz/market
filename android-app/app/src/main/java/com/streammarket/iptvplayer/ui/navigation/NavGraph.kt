package com.streammarket.iptvplayer.ui.navigation

import androidx.compose.runtime.Composable
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.streammarket.iptvplayer.IptvApp
import com.streammarket.iptvplayer.ui.GenericViewModelFactory
import com.streammarket.iptvplayer.ui.home.HomeScreen
import com.streammarket.iptvplayer.ui.home.HomeViewModel
import com.streammarket.iptvplayer.ui.login.LoginScreen
import com.streammarket.iptvplayer.ui.login.LoginViewModel
import com.streammarket.iptvplayer.ui.player.PlayerScreen
import com.streammarket.iptvplayer.ui.player.PlayerViewModel
import java.net.URLDecoder
import java.net.URLEncoder
import java.nio.charset.StandardCharsets

object Routes {
    const val LOGIN = "login"
    const val HOME = "home"
    const val PLAYER = "player/{streamId}/{name}"

    fun player(streamId: String, name: String): String {
        val encodedName = URLEncoder.encode(name, StandardCharsets.UTF_8.toString())
        return "player/$streamId/$encodedName"
    }
}

@Composable
fun IptvNavGraph(app: IptvApp, startDestination: String = Routes.LOGIN) {
    val navController = rememberNavController()

    NavHost(navController = navController, startDestination = startDestination) {
        composable(Routes.LOGIN) {
            val viewModel: LoginViewModel = viewModel(
                factory = GenericViewModelFactory { LoginViewModel(app.authRepository) }
            )
            LoginScreen(
                viewModel = viewModel,
                onLoginSuccess = {
                    navController.navigate(Routes.HOME) {
                        popUpTo(Routes.LOGIN) { inclusive = true }
                    }
                }
            )
        }

        composable(Routes.HOME) {
            val viewModel: HomeViewModel = viewModel(
                factory = GenericViewModelFactory { HomeViewModel(app.liveRepository) }
            )
            HomeScreen(
                viewModel = viewModel,
                onChannelClick = { channel ->
                    navController.navigate(Routes.player(channel.stream_id, channel.name))
                }
            )
        }

        composable(
            route = Routes.PLAYER,
            arguments = listOf(
                navArgument("streamId") { type = NavType.StringType },
                navArgument("name") { type = NavType.StringType }
            )
        ) { backStackEntry ->
            val streamId = backStackEntry.arguments?.getString("streamId").orEmpty()
            val encodedName = backStackEntry.arguments?.getString("name").orEmpty()
            val name = URLDecoder.decode(encodedName, StandardCharsets.UTF_8.toString())

            val viewModel: PlayerViewModel = viewModel(
                factory = GenericViewModelFactory { PlayerViewModel(app.liveRepository, streamId, name) }
            )
            PlayerScreen(viewModel = viewModel)
        }
    }
}
