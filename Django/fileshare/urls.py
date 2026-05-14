from django.urls import path
from . import views

urlpatterns = [
    path('', views.home, name='home'),
    path('upload/', views.upload_file, name='upload_file'),
    path('d/<str:hash_value>/', views.download_file, name='download_file'),
]