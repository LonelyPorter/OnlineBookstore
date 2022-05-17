package com.example.bookstore;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // Login Button
        Button loginBtn = findViewById(R.id.login_btn);
        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // get email
                EditText editEmail = findViewById(R.id.editText_email);
                String email = editEmail.getText().toString();
                // get password
                EditText editPwd = findViewById(R.id.editText_pwd);
                String pwd = editPwd.getText().toString();

                Log.d("Login", email + " " + pwd);

                StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "login.php",
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                try {
                                    Log.d("Login", "response:" + response);
                                    JSONArray jsonArray = new JSONArray(response);
                                    JSONObject jsonObject = jsonArray.getJSONObject(0);
                                    String id = jsonObject.getString("id");
                                    Log.d("Login", "id is:" + id);
                                    if (id != "null") {
                                        Intent store = new Intent(MainActivity.this, StoreActivity.class);
                                        store.putExtra("id", id);
                                        startActivity(store);
                                    }
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            }
                        }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {

                    }
                }) {
                    @Override
                    protected Map<String, String> getParams() throws AuthFailureError {
                        Map<String, String> map = new HashMap<String, String>();
                        map.put("guest", "false");
                        map.put("email", email);
                        map.put("password", pwd);
                        return map;
                    }
                };

                requestQueue.add(stringRequest);
            }
        });

        // Guest button
        Button guestBtn = (Button) findViewById(R.id.guest_btn);
        guestBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "login.php",
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                Log.d("Guest", response);
                                Intent store = new Intent(MainActivity.this, StoreActivity.class);

                                // pass in data
                                String id = null;
                                try {
                                    JSONArray jsonArray = new JSONArray(response);
                                    JSONObject jsonObject = jsonArray.getJSONObject(0);
                                    id = jsonObject.getString("id");
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }

                                // guest id
                                store.putExtra("id", id);


                                // start activity
                                startActivity(store);
                            }
                        }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("Guest", error.getMessage(), error);
                    }
                }) {
                    @Override
                    protected Map<String, String> getParams() throws AuthFailureError {
                        Map<String, String> map = new HashMap<String, String>();
                        map.put("guest", "true");
                        return map;
                    }
                };

                requestQueue.add(stringRequest);
            }
        });

        // Sign up
        Button signUp = findViewById(R.id.signup_btn);
        // sign up button
        signUp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(MainActivity.this, SignUpActivity.class);

                startActivity(intent);
            }
        });

        Button publisher = findViewById(R.id.publisher_btn);
        publisher.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(MainActivity.this, PublisherLoginActivity.class);
                startActivity(intent);
            }
        });

    }

}