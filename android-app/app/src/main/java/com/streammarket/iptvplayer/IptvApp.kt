package com.streammarket.iptvplayer

import android.app.Application
import com.streammarket.iptvplayer.data.local.SessionManager
import com.streammarket.iptvplayer.data.repository.AuthRepository
import com.streammarket.iptvplayer.data.repository.LiveRepository

class IptvApp : Application() {

    lateinit var sessionManager: SessionManager
        private set
    lateinit var authRepository: AuthRepository
        private set
    lateinit var liveRepository: LiveRepository
        private set

    override fun onCreate() {
        super.onCreate()
        sessionManager = SessionManager(this)
        authRepository = AuthRepository(sessionManager)
        liveRepository = LiveRepository()
    }
}
