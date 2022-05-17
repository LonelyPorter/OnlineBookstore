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

public class PublisherLoginActivity extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_publisher_login);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // Login Button
        Button loginBtn = findViewById(R.id.publisherLogin_btn);
        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // get name
                EditText editName = findViewById(R.id.editText_publisherLoginName);
                String publisherLoginName = editName.getText().toString();

                Log.d("PublisherLogin", publisherLoginName);

                StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "publisher.php",
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                try {
                                    Log.d("PublisherLogin", "response:" + response);
                                    JSONArray jsonArray = new JSONArray(response);
                                    // name and address
                                    JSONObject jsonObject = jsonArray.getJSONObject(0);
                                    String name = jsonObject.getString("name");
                                    String address = jsonObject.getString("address");

                                    // books
                                    String[] titles = new String[jsonArray.length() - 1];
                                    for (int i = 1; i < jsonArray.length(); i++) {
                                        titles[i - 1] = jsonArray.getJSONObject(i).getString("title");
                                    }

                                    if (name != "null") {
                                        Intent intent = new Intent(PublisherLoginActivity.this, PublisherActivity.class);
                                        intent.putExtra("name", name);
                                        intent.putExtra("address", address);
                                        intent.putExtra("titles", titles);

                                        startActivity(intent);
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
                        map.put("name", publisherLoginName);
                        return map;
                    }
                };

                requestQueue.add(stringRequest);
            }
        });
    }

}
